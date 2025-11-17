<?php

namespace App\Http\Controllers;

use App\Exports\DeliveryOrderExport;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItem;
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

class DeliveryOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function __construct()
    {
        $this->middleware('permission:delivery-note-list|delivery-note-create|delivery-note-edit|delivery-note-delete', ['only' => ['index','store']]);
        $this->middleware('permission:delivery-note-create', ['only' => ['create','store']]);
        $this->middleware('permission:delivery-note-edit|delivery-note-item-add', ['only' => ['edit','update']]);
        $this->middleware('permission:delivery-note-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DeliveryOrder::query();
            if ($request->has('warehouse_id') && $request->warehouse_id) {
                $data->where('warehouse_id', $request->warehouse_id);
            }
            $data->with(['warehouse:id,owner,project', 'createUser:id,name']);
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('warehouse_id', function ($row) {
                    return $row->warehouse->project;
                })
                ->editColumn('created_by', function ($row) {
                    return $row->createUser->name;
                })
                ->editColumn('date', function ($row) {
                    return $row->date->format('d-m-Y');
                })
                ->addColumn('action', function($row){
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (auth()->user()->canany(['delivery-note-edit', 'delivery-note-item-add', 'delivery-note-item-delete'])) {
                        $editBtn = '<a href="'. route('delivery.edit', $row->id) .'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a> ';
                    }
                    
                    if (auth()->user()->canany('delivery-note-delete')) {
                        $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\''. route('delivery.destroy', $row->id) .'\', \'tblDeliveryOrder\')"><i class="fas fa-trash-alt"></i> </button>';
                    }
                    return $editBtn.$deleteBtn;
                })
                ->filterColumn('warehouse_id', function($query, $keyword) {
                    $query->whereHas('warehouse', function($q) use ($keyword) {
                        $q->whereRaw('LOWER(project) LIKE ?', ["%$keyword%"]);
                    });
                })
                ->filterColumn('created_by', function($query, $keyword) {
                    $query->whereHas('createUser', function($q) use ($keyword) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%$keyword%"]);
                    });
                })
                ->orderColumn('warehouse_id', function ($query, $order) {
                    $query->orderBy('warehouses.project', $order == 'desc' ? 'asc' : 'desc');
                })
                ->orderColumn('created_by', function ($query, $order) {
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
        return view('deliveryorder.index', ['warehouses'=>$warehouses]);
    }
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $status = $request->status;
            $warehouse_id = $request->warehouse_id;
            $filter_status = '';
        
            $datas = DB::table('delivery_orders as dlo')
                ->join('delivery_order_items as doi', 'dlo.id', '=', 'doi.delivery_order_id')
                ->join('material_request_items as mri', 'doi.material_request_item_id', '=', 'mri.id')
                ->join('items as i', 'mri.item_id', '=', 'i.id')
                ->select(
                    'dlo.do_number',
                    DB::raw("DATE_FORMAT(dlo.date, '%d-%m-%Y') as do_date"),
                    DB::raw("CONCAT(i.code, ' - ', i.name) as item"),
                    'doi.qty as do_qty',
                    'doi.received_qty',
                    'doi.balance',
                    DB::raw("
                        CASE 
                            WHEN doi.balance = 0 THEN 'Telah diterima' 
                            WHEN doi.balance <> doi.qty THEN 'Terima Sebagian'
                            ELSE 'Belum diterima'
                        END as status
                    ")
                )
                ->where('dlo.warehouse_id', $warehouse_id);

            if($status == 1) {
                $datas->whereRaw('doi.balance = 0');
            }
            // Telah Terima Sebagian
            elseif($status == 2){
                $datas->whereRaw('doi.balance <> doi.qty AND doi.balance > 0');
            }
            // Belum Terima
            elseif($status == 3){
                $datas->whereRaw('doi.balance = doi.qty');
            }
        
            // Use the collection for Datatables
            return Datatables::of($datas)
                ->addIndexColumn()
                
                ->filterColumn('item', function($query, $keyword) {
                    $query->whereRaw("CONCAT(i.code, ' - ', i.name) LIKE ?", ["%$keyword%"]);
                })
                 // Filtering on the status field
                ->filterColumn('status', function($query, $keyword) {
                    $query->whereRaw("
                        CASE 
                            WHEN doi.balance = 0 THEN 'Telah diterima' 
                            WHEN doi.balance <> doi.qty THEN 'Terima Sebagian'
                            ELSE 'Belum diterima'
                        END LIKE ?", ["%$keyword%"]);
                })

                // Other filters if needed
                ->filterColumn('do_qty', function($query, $keyword) {
                    $query->whereRaw("doi.qty LIKE ?", ["%$keyword%"]);
                })
                ->filterColumn('received_qty', function($query, $keyword) {
                    $query->whereRaw("doi.received_qty LIKE ?", ["%$keyword%"]);
                })
                ->filterColumn('balance', function($query, $keyword) {
                    $query->whereRaw("doi.balance LIKE ?", ["%$keyword%"]);
                })
                ->filterColumn('created_by', function($query, $keyword) {
                    $query->whereHas('createUser', function($q) use ($keyword) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%$keyword%"]);
                    });
                })
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
        return view('deliveryorder.show', ['warehouses'=>$warehouses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    
    public function create()
    {
        $user = auth()->user();
        $warehouses = $user->warehouses->mapWithKeys(function ($item) {
            return [$item->id => $item->spk_number . ' - ' . $item->project];
        })->all();
        
        return view('deliveryorder.create', ['warehouses'=>$warehouses]);
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
            'police_no'=>'required',
            'receipent'=>'required',
            'address'=>'required',
        ]);

        DB::beginTransaction();
        
        try {
            $warehouse = Warehouse::findOrFail($request->input('warehouse_id'));
            $number_format = $warehouse->sequence_format . '/SJ';
            $do_number = MaterialRequest::generateAutoNumber($number_format, $warehouse->id, 'DO');
            // Create the material request
            $materialRequest = DeliveryOrder::create([
                'do_number' => $do_number,
                'date' => $request->input('date'),
                'police_no' => $request->input('police_no'),
                'receipent' => $request->input('receipent'),
                'address' => $request->input('address'),
                'warehouse_id' => $request->input('warehouse_id'),
                'created_by' => auth()->user()->id
            ]);
            /*
            // Create the material request items
            $items = $request->input('material_request_item_id');
            $quantities = $request->input('qty');

            foreach ($items as $index => $item) {
                DeliveryOrderItem::create([
                    'delivery_order_id' => $materialRequest->id,
                    'material_request_item_id' => $item,
                    'qty' => $quantities[$index],
                ]);
            }*/
            DB::commit();
            return redirect()->route('delivery.edit', $materialRequest->id)->with('success', 'Surat Jalan Berhasil dibuat, silahkan tambahj.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('delivery.create')->with('error', 'Gagal membuat Surat Jalan !');
        }
    }
    public function storeItems($id, Request $request)
    {
        DB::beginTransaction();
        
        try {
            $items = $request->input('material_request_item_id');
            $quantities = $request->input('qty');
            $po_numbers = $request->input('po');
            $po_dates = $request->input('po_date');
            $vendors = $request->input('vendor');

            foreach ($items as $index => $item) {
                if (!empty($quantities[$index])) {
                    DeliveryOrderItem::create([
                        'delivery_order_id' => $id,
                        'material_request_item_id' => $item,
                        'qty' => $quantities[$index],
                        'po_number' => $po_numbers[$index],
                        'po_date' => $po_dates[$index],
                        'vendor' => $vendors[$index],
                    ]);

                    // Update Material request item
                    $material_request_item = MaterialRequestItem::findOrFail($item);
                    $material_request_item->do_qty = $material_request_item->do_qty + $quantities[$index];
                    $material_request_item->updated_at = now();
                    $material_request_item->save();

                    // Update material request status
                    $material_request = MaterialRequest::findOrFail($material_request_item->mr_id);
                    $material_request->status_id = 2;
                    $material_request->updated_at = now();
                    $material_request->save();
                }
            }
            DB::commit();
            return redirect()->route('delivery.edit', $id)->with('success', 'Barang Berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('delivery.edit', $id)->with('error', 'Gagal menambahkan barang.');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(DeliveryOrder $deliveryOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Fetch related data needed for the edit form
        $deliveryOrder = DeliveryOrder::findOrFail($id);
        $user = auth()->user();
        $warehouses = $user->warehouses->mapWithKeys(function ($item) {
            return [$item->id => $item->spk_number . ' - ' . $item->project];
        })->all();
        $materialRequests = MaterialRequest::where('warehouse_id', $deliveryOrder->warehouse_id)
                                            ->whereHas('items', function($q) {
                                                $q->whereColumn('do_qty', '<', 'qty');
                                            })
                                            ->get();
        $deliveryOrderItems = $deliveryOrder->DeliveryItems;

        // Return the view with the existing delivery order and other necessary data
        return view('deliveryorder.edit', compact('deliveryOrder', 'warehouses', 'materialRequests', 'deliveryOrderItems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $deliveryOrder = DeliveryOrder::findOrFail($id);
        $deliveryOrder->update($request->only(['date', 'warehouse_id', 'police_no', 'receipent', 'address']));
        return redirect()->route('delivery.index')->with('success', 'Surat Jalan updated successfully.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Delete the delivery order
            DeliveryOrder::find($id)->delete();
            return response()->json(['success'=>true, 'msg' => 'Surat Jalan berhasil dihapus!', 200]);
    
        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'msg' => 'Gagal menghapus surat jalan!'. $e, 400]);
        }
    
    }
    
    public function export($id, $type)
    {
        $logo = Cache::remember('site_logo', 60 * 24, function () {
            return Site::first()->logo_path ?? 'storage/images/logo.png';
        });
        $data = DeliveryOrder::findOrFail($id);
        if($type == 'EXCEL'){
            return Excel::download(new DeliveryOrderExport($data), 'Surat Jalan.xlsx');
        }elseif($type == 'PDF'){
            $mpdf = new Mpdf([
                'format' => 'A4', // 'L' stands for landscape orientation
                'margin_top' => 8, // Set top margin (in mm)
                'margin_bottom' => 8, // Set bottom margin (in mm)
                'margin_left' => 8, // Set left margin (in mm)
                'margin_right' => 8, // Set right margin (in mm)
                'tempDir' => __DIR__ . '/tmp', // Change tempDir if necessary
                'debug' => true, // Enable debugging
            ]);
            
            $mpdf->imageVars['logo'] = file_get_contents($_SERVER['DOCUMENT_ROOT'] .'/storage/'. $logo);
            // Write the HTML content to the PDF
            $html = view('report.PDF.sj', ['sj'=>$data])->render();
            $mpdf->WriteHTML($html);
            
            $mpdf->SetHTMLFooter(
                '<div style="font-size: 10px; text-align: left;">
                    Dicetak oleh ' . auth()->user()->name . ' pada tanggal ' . now() . '
                </div>'
            );

            return $mpdf->Output('Surat Jalan.pdf', 'I');
        }
    }
    public function getDeliveryItems(Request $request)
    {
        $doNumber = $request->input('do_number');

        // Find the delivery order by the provided number
        $deliveryOrder = DeliveryOrder::where('do_number', $doNumber)->first();

        if (!$deliveryOrder) {
            return response()->json('<div class="alert alert-warning">Surat Jalan tidak ditemukan !.</div>');
        }
       // Get the items associated with the delivery order
        $items = $deliveryOrder->deliveryItems;

        // Render the items list view
        $html = view('arrival.delivery-list', compact('items'))->render();

        return response()->json($html);

    }

    public function deliveryByWarehouse($id)
    {
        $deliveryOrders = DeliveryOrder::where('warehouse_id', $id)->get(['id', 'do_number']);
        return response()->json($deliveryOrders);
    }

    
    public function destroyItems($id)
    {
        DB::beginTransaction();
    
        try {
            // Find the DeliveryOrderItem by ID
            $deliveryOrderItem = DeliveryOrderItem::findOrFail($id);
            
            // Get the quantity and material_request_item_id
            $quantity = $deliveryOrderItem->qty;
            $materialRequestItemId = $deliveryOrderItem->material_request_item_id;

            // Rollback qty in MaterialRequestItem
            $materialRequestItem = MaterialRequestItem::findOrFail($materialRequestItemId);
            $materialRequestItem->do_qty = $materialRequestItem->do_qty - $quantity;
            $materialRequestItem->updated_at = now();
            $materialRequestItem->save();

            // Delete the DeliveryOrderItem
            $deliveryOrderItem->delete();

            DB::commit();
            return response()->json(['success'=>true, 'msg' => 'Barang Berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack();
            // Optionally log the error or handle it as needed
            return response()->json(['success'=>false, 'msg' => 'Gagal menghapus barang!']);
        }

    }
}
