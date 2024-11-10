<?php

namespace App\Http\Controllers;

use App\Models\ItemOut;
use App\Models\ItemOutDetail;
use App\Models\MaterialRequest;
use App\Models\StockCard;
use App\Models\Warehouse;
use App\Models\WarehouseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
class ItemOutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ItemOut::query();
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
            ->addColumn('action', function($row){
                $editBtn = '';
                $deleteBtn = '';
                
                if (auth()->user()->can('item-edit')) {
                    $editBtn = '<a href="'. route('out.edit', $row->id) .'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a> ';
                }
                
                if (auth()->user()->can('item-delete')) {
                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\''. route('out.destroy', $row->id) .'\', \'tblIssued\')"><i class="fas fa-trash-alt"></i> </button>';
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
        return view('issued.index', ['warehouses'=>$warehouses]);
    }

    public function history()
    {
        $today = now();
        $startDate = $today->copy()->subDays(7)->format('Y-m-d');
        $endDate = $today->format('Y-m-d');
        
        $user = auth()->user();
        $warehouses = $user->warehouses->mapWithKeys(function ($item) {
            return [$item->id => $item->spk_number . ' - ' . $item->project];
        })->all();
    
        return view('issued.history', compact('warehouses', 'startDate', 'endDate'));
    }

    public function searchData(Request $request)
    {
        // Validate request data
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date',
        ]);

        $warehouse_id = $request->input('warehouse_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query your data
        $query = ItemOutDetail::with(['itemOut', 'item'])
                            ->whereHas('itemOut', function ($query) use ($warehouse_id, $startDate, $endDate) {
                                $query->where('item_outs.warehouse_id', $warehouse_id);
                                $query->whereBetween(DB::raw('DATE(item_outs.date)'), [$startDate, $endDate]);
                            });
        // Return DataTables response
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('io_number', function($row){
                return $row->itemOut->io_number;
            })
            ->addColumn('item', function($row){
                return '['. $row->item->code . '] '. $row->item->name;
            })
            
            ->addColumn('out_date', function($row){
                return $row->itemOut->date;
            })
            
            ->addColumn('action', function($row){
                $editBtn = '';
                $deleteBtn = '';
                
                if (auth()->user()->can('item-edit')) {
                    $editBtn = '<a href="'. route('delivery.edit', $row->id) .'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a> ';
                }
                
                if (auth()->user()->can('item-delete')) {
                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\''. route('delivery.destroy', $row->id) .'\', \'tblMaterialRequest\')"><i class="fas fa-trash-alt"></i> </button>';
                }
                return $editBtn.$deleteBtn;
            })
            ->orderColumn('io_number', function ($query, $order) {
                // Use raw SQL to join and order by the correct column
                $query->join('item_outs', 'item_out_details.item_out_id', '=', 'item_outs.id')
                    ->orderBy('item_outs.io_number', $order);
            })
            ->orderColumn('item', function ($query, $order) {
                // Use raw SQL to join and order by the correct column
                $query->join('items', 'item_out_details.item_id', '=', 'items.id')
                    ->orderBy('items.code', $order);
            })
            ->rawColumns(['action'])
            ->make(true);
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
        
        return view('issued.create', ['warehouses'=>$warehouses]);
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
            'remark'=>'required',
        ]);

        DB::beginTransaction();
        
        try {
            $warehouse = Warehouse::findOrFail($request->input('warehouse_id'));
            $number_format = $warehouse->sequence_format . '/OUT';
            $do_number = MaterialRequest::generateAutoNumber($number_format, $warehouse->id, 'IO');
            // Create the material request
            $materialRequest = ItemOut::create([
                'io_number' => $do_number,
                'date' => $request->input('date'),
                'warehouse_id' => $request->input('warehouse_id'),
                'remark' => $request->input('remark'),
                'created_by' => auth()->user()->id
            ]);
            DB::commit();
            return redirect()->route('out.edit', $materialRequest->id)->with('success', 'Informasi Barang Keluar Berhasil dibuat, silahkan tambah Barang.');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->route('out.create')->with('error', 'Gagal membuat barang keluar !');
        }
    }
    public function storeItems($id, Request $request)
    {
        
        DB::beginTransaction();
        $itemOut = ItemOUt::findOrFail($id);
        $warehouse_id = $itemOut->warehouse->id;
        
        try {
            $items = $request->input('item_id');
            $quantities = $request->input('qty');
            $remarks = $request->input('remark');

            foreach ($items as $index => $item) {
                if (!empty($quantities[$index])) {
                    $new_item = ItemOutDetail::create([
                        'item_out_id' => $itemOut->id,
                        'item_id' => $item,
                        'qty' => $quantities[$index],
                        'remark' => $remarks[$index],
                    ]);
                    $warehouseItem = WarehouseItem::updateOrCreate(
                        [
                            'warehouse_id' => $warehouse_id,
                            'item_id' => $item,
                        ],
                        [
                            'qty' => DB::raw('qty - ' . $quantities[$index]),
                        ]
                    );
                    StockCard::firstOrCreate(
                        [
                            'warehouse_id'=> $warehouse_id,
                            'ref_id' => $new_item->id,
                            'item_id'=> $item,
                            'type'=>'out',
                        ],
                        [
                            'qty'=>$new_item->qty * -1,
                            'date'=> $itemOut->date,
                        ]
                    );
                }
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Barang Berhasil ditambahkan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Gagal menambahkan barang.'.$e]);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemOut $itemOut)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Fetch related data needed for the edit form
        $itemOut = ItemOut::findOrFail($id);
        $user = auth()->user();
        $itemOutDetails = $itemOut->details;
        
        

        // Return the view with the existing delivery order and other necessary data
        return view('issued.edit', compact('itemOut', 'itemOutDetails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemOut $itemOut)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemOut $itemOut)
    {
        //
    }
    public function destroyItems($id)
    {
        DB::beginTransaction();
        
        try {
            // Find the ItemOutDetail by ID
            $itemOutDetail = ItemOutDetail::findOrFail($id);
            
            // Get relevant data
            $quantity = $itemOutDetail->qty;
            $itemId = $itemOutDetail->item_id;
            $itemOutId = $itemOutDetail->item_out_id;

            // Get the WarehouseItem related to this item and warehouse
            $itemOut = ItemOut::findOrFail($itemOutId);
            $warehouseId = $itemOut->warehouse->id;

            // Rollback qty in WarehouseItem
            $warehouseItem = WarehouseItem::where('warehouse_id', $warehouseId)
                ->where('item_id', $itemId)
                ->first();

            if ($warehouseItem) {
                $warehouseItem->qty += $quantity; // Increase the quantity back
                $warehouseItem->save();
            }

            // Delete the ItemOutDetail
            $itemOutDetail->delete();

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Barang Berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            // Optionally log the error or handle it as needed
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus barang. ' . $e]);
        }
    }

}
