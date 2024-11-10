<?php

namespace App\Http\Controllers;

use App\Exports\MaterialRequestExport;
use App\Models\Item;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\Warehouse;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Cache;

class MaterialRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = MaterialRequest::query();
            if ($request->has('warehouse_id') && $request->warehouse_id) {
                $data->where('warehouse_id', $request->warehouse_id);
            }
            $data->with(['warehouse:id,owner,project', 'requestor:id,name', 'status:id,status']);
            

            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('warehouse_id', function ($row) {
                return $row->warehouse->project;
            })
            ->editColumn('requested_by', function ($row) {
                return $row->requestor->name;
            })
            ->editColumn('status_id', function ($row) {
                return $row->status->status;
            })
            ->addColumn('action', function($row){
                $editBtn = '';
                $deleteBtn = '';
                
                if (auth()->user()->can('item-edit')) {
                    $editBtn = '<a href="'. route('material.edit', $row->id) .'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a> ';
                }
                
                if (auth()->user()->can('item-delete')) {
                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\''. route('material.destroy', $row->id) .'\', \'tblMaterialRequest\')"><i class="fas fa-trash-alt"></i> </button>';
                }
                return $editBtn.$deleteBtn;
            })
            ->filterColumn('warehouse_id', function($query, $keyword) {
                $query->whereHas('warehouse', function($q) use ($keyword) {
                    $q->whereRaw('LOWER(project) LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('requested_by', function($query, $keyword) {
                $query->whereHas('requestor', function($q) use ($keyword) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('status_id', function($query, $keyword) {
                $query->whereHas('status', function($q) use ($keyword) {
                    $q->whereRaw('LOWER(status) LIKE ?', ["%$keyword%"]);
                });
            })
            ->orderColumn('status_id', function ($query, $order) {
                $query->orderBy('status.id', $order == 'desc' ? 'asc' : 'desc');
            })
            ->orderColumn('warehouse_id', function ($query, $order) {
                $query->orderBy('warehouses.project', $order == 'desc' ? 'asc' : 'desc');
            })
            ->orderColumn('requested_by', function ($query, $order) {
                $query->orderBy('users.name', $order == 'desc' ? 'asc' : 'desc');
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        $user = auth()->user();
        if ($user && $user->hasRole('Super Admin')) {
            $warehouses = Warehouse::all()->mapWithKeys(function ($item) {
                return [$item->id => $item->spk_number . ' - ' . $item->project];
            })->all();
        }else {
            $warehouses = $user->warehouses->mapWithKeys(function ($item) {
                return [$item->id => $item->spk_number . ' - ' . $item->project];
            })->all();
        }
        return view('material.index', ['warehouses'=>$warehouses]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $items = Item::with('uom')->get();
        $warehouses = $user->warehouses->mapWithKeys(function ($item) {
            return [$item->id => $item->spk_number . ' - ' . $item->project];
        })->all();
        return view('material.create', ['warehouses'=>$warehouses, 'items'=>$items]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'date' => 'required|date',
            'warehouse_id' => 'required|exists:warehouses,id',
            'items.*' => 'required|exists:items,id',
            'qty.*' => 'required|numeric|min:1',
            'uom.*' => 'required|string',
            'date_needed.*' => 'required|date',
            'boq.*' => 'nullable|string',
            'description.*' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            $warehouse = Warehouse::findOrFail($request->input('warehouse_id'));
            $number_format = $warehouse->sequence_format;
            $mr_number = MaterialRequest::generateAutoNumber($number_format, $warehouse->id, 'MR');
            // Create the material request
            $materialRequest = MaterialRequest::create([
                'mr_number' => $mr_number,
                'date' => $request->input('date'),
                'warehouse_id' => $request->input('warehouse_id'),
                'requested_by' => auth()->user()->id,
                'status_id' => 1
            ]);

            // Create the material request items
            $items = $request->input('items');
            $quantities = $request->input('qty');
            $dates_needed = $request->input('date_needed');
            $boqs = $request->input('boq');
            $descriptions = $request->input('description');
            $checks_m = $request->input('check_m', []);
            $checks_t = $request->input('check_t', []);
            $checks_he = $request->input('check_he', []);
            $checks_c = $request->input('check_c', []);
            $checks_o = $request->input('check_o', []);

            foreach ($items as $index => $item) {
                MaterialRequestItem::create([
                    'mr_id' => $materialRequest->id,
                    'item_id' => $item,
                    'qty' => $quantities[$index],
                    'date_needed' => $dates_needed[$index],
                    'boq_code' => $boqs[$index],
                    'description' => $descriptions[$index],
                    'check_m' => in_array($index, array_keys($checks_m)),
                    'check_t' => in_array($index, array_keys($checks_t)),
                    'check_he' => in_array($index, array_keys($checks_he)),
                    'check_c' => in_array($index, array_keys($checks_c)),
                    'check_o' => in_array($index, array_keys($checks_o)),
                ]);
            }
            DB::commit();
            return redirect()->route('material.index')->with('success', 'SPM Berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('material.create')->with('error', 'Gagal membuat SPM !');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(MaterialRequest $materialRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = auth()->user();
        $materialRequest = MaterialRequest::with('items')->findOrFail($id);
        $warehouses = $user->warehouses->mapWithKeys(function ($item) {
            return [$item->id => $item->spk_number . ' - ' . $item->project];
        })->all();
        $items = Item::with(['uom'])->get();

        return view('material.edit', compact('materialRequest', 'warehouses', 'items'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
    
        try {
            // Find the material request
            $materialRequest = MaterialRequest::findOrFail($id);
    
            // Update material request details
            $materialRequest->update([
                'date' => $request->input('date'),
            ]);
    
            // Get existing item IDs
            $existingItemIds = $materialRequest->items()->pluck('item_id')->toArray();
    
            // Get updated data from the request
            $items = $request->input('items', []);
            $qtys = $request->input('qty', []);
            $uoms = $request->input('uom', []);
            $datesNeeded = $request->input('date_needed', []);
            $boqs = $request->input('boq', []);
            $checksM = $request->input('check_m', []);
            $checksT = $request->input('check_t', []);
            $checksHE = $request->input('check_he', []);
            $checksC = $request->input('check_c', []);
            $checksO = $request->input('check_o', []);
            $descriptions = $request->input('description', []);
    
            // Ensure arrays are the same length
            $count = count($items);
            for ($i = 0; $i < $count; $i++) {
                if (!isset($items[$i])) continue; // Skip if the item is not set
    
                $materialRequest->items()->updateOrCreate(
                    ['item_id' => $items[$i]],
                    [
                        'qty' => isset($qtys[$i]) ? $qtys[$i] : null,
                        'uom' => isset($uoms[$i]) ? $uoms[$i] : null,
                        'date_needed' => isset($datesNeeded[$i]) ? $datesNeeded[$i] : null,
                        'boq_code' => isset($boqs[$i]) ? $boqs[$i] : null,
                        'check_m' => isset($checksM[$i]) ? (bool) $checksM[$i] : false,
                        'check_t' => isset($checksT[$i]) ? (bool) $checksT[$i] : false,
                        'check_he' => isset($checksHE[$i]) ? (bool) $checksHE[$i] : false,
                        'check_c' => isset($checksC[$i]) ? (bool) $checksC[$i] : false,
                        'check_o' => isset($checksO[$i]) ? (bool) $checksO[$i] : false,
                        'description' => isset($descriptions[$i]) ? $descriptions[$i] : null,
                    ]
                );
            }
    
            // Delete items that are no longer in the request
            $newItemIds = array_filter($items);
            $itemsToDelete = array_diff($existingItemIds, $newItemIds);
            if (!empty($itemsToDelete)) {
                $materialRequest->items()->whereIn('item_id', $itemsToDelete)->delete();
            }
            $hasSufficientQty = $materialRequest->items()->whereColumn('received_qty', '<', 'qty')->exists();
            if ($hasSufficientQty) {
                $materialRequest->status_id = 3; //Partial Completed
            }else{
                $materialRequest->status_id = 4; //Completed
            }
            $materialRequest->save();
            DB::commit();
    
            return redirect()->back()->with('success', 'Material Request updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the error, log it, or return an error response
            return redirect()->back()->withErrors('An error occurred while updating the material request: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        MaterialRequest::find($id)->delete();
        return response()->json(['success'=>true, 'msg' => 'Material Request deleted successfully!']);
    }


    public function export($id, $type)
    {
        $logo = Cache::remember('site_logo', 60 * 24, function () {
            return Site::first()->logo_path ?? 'storage/images/logo.png';
        });
        $data = MaterialRequest::findOrFail($id);
        if($type == 'EXCEL'){
            return Excel::download(new MaterialRequestExport($data), 'SPM.xlsx');
        }elseif($type == 'PDF'){
            $mpdf = new Mpdf([
                'format' => 'A4-L', // 'L' stands for landscape orientation
                'margin_top' => 8, // Set top margin (in mm)
                'margin_bottom' => 8, // Set bottom margin (in mm)
                'margin_left' => 8, // Set left margin (in mm)
                'margin_right' => 8, // Set right margin (in mm)
                'tempDir' => __DIR__ . '/tmp', // Change tempDir if necessary
                'debug' => true, // Enable debugging

            ]);
            $mpdf->showImageErrors = true;
            $mpdf->SetCompression(false);
            $mpdf->imageVars['logo'] = file_get_contents($_SERVER['DOCUMENT_ROOT'] .'/storage/'. $logo);
            // dd(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/storage/images/logo.png'));
            // Write the HTML content to the PDF
            $html = view('report.PDF.spm', ['spm'=>$data])->render();
            $mpdf->WriteHTML($html);

            $mpdf->SetHTMLFooter(
                '<div style="font-size: 10px; text-align: left; border:0">
                    Dicetak oleh ' . auth()->user()->name . ' pada tanggal ' . now() . '
                </div>'
            );
            return $mpdf->Output('SPM.pdf', 'I');
        }
    }

    public function listByWarehouse(Request $request)
    {
        if ($request->ajax()) {
            $data = MaterialRequest::query();

            // Check if warehouse_id is provided and filter the data accordingly
            if ($request->has('warehouse_id') && $request->warehouse_id) {
                $data->where('warehouse_id', $request->warehouse_id)
                    ->whereHas('items', function($q) {
                        $q->whereColumn('do_qty', '<', 'qty');
                    });
            } else {
                return response()->json([]);  // Return empty if no warehouse_id is provided
            }

            // Eager load necessary relationships
            $data->with([
                'warehouse:id,owner,project',
                'requestor:id,name',
                'status:id,status'
            ]);

            // Return the filtered data
            return response()->json($data->get());
        }

        return response()->json(['error' => 'Invalid request'], 400);  // Handle non-ajax requests
    }
    public function itemListByMaterialRequest(Request $request){
        if ($request->ajax()) {
            $data = MaterialRequestItem::query();
            if ($request->has('mr_id') && $request->mr_id) {
                $data->where('mr_id', $request->mr_id)
                    ->whereColumn('do_qty', '<', 'qty');
            }else{
                return [];
            }
            $data->with(['item', 'item.uom']);
            return $data->get();
        }
    }
    
}
