<?php

namespace App\Http\Controllers;


use App\Models\DeliveryOrderItem;
use App\Models\ItemArrival;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\StockCard;
use App\Models\WarehouseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;


class ItemArrivalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = now();
        $startDate = $today->copy()->subDays(7)->format('Y-m-d');
        $endDate = $today->format('Y-m-d');
        
        $user = auth()->user();
        $warehouses = $user->warehouses->mapWithKeys(function ($item) {
            return [$item->id => $item->spk_number . ' - ' . $item->project];
        })->all();
    
        return view('arrival.history', compact('warehouses', 'startDate', 'endDate'));
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
        $query = ItemArrival::with([
                                    'deliveryOrderItem',
                                    'deliveryOrderItem.deliveryOrder',
                                    'deliveryOrderItem.materialRequestItem.item'
                                ])
                                ->whereBetween(DB::raw('DATE(arrival_date)'), [$startDate, $endDate])
                                ->whereHas('deliveryOrderItem.deliveryOrder.warehouse', function ($query) use ($warehouse_id) {
                                    $query->where('warehouses.id', $warehouse_id);
                                });
    
        // Return DataTables response
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('warehouse_id', function ($row) {
                return $row->deliveryOrderItem->deliveryOrder->warehouse->spk_number;
            })
            ->addColumn('do_number', function($row){
                return $row->deliveryOrderItem->deliveryOrder->do_number;
            })
            ->addColumn('item', function($row){
                return '['. $row->deliveryOrderItem->materialRequestItem->item->code . '] '. $row->deliveryOrderItem->materialRequestItem->item->name;
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
            ->filterColumn('warehouse_id', function($query, $keyword) {
                $query->whereHas('warehouse', function($q) use ($keyword) {
                    $q->whereRaw('LOWER(project) LIKE ?', ["%$keyword%"]);
                });
            })
            ->orderColumn('do_number', function ($query, $order) {
                // Use raw SQL to join and order by the correct column
                $query->join('delivery_order_items', 'item_arrivals.delivery_order_item_id', '=', 'delivery_order_items.id')
                    ->join('delivery_orders', 'delivery_order_items.delivery_order_id', '=', 'delivery_orders.id')
                    ->orderBy('delivery_orders.do_number', $order);
            })
            ->orderColumn('warehouse_id', function ($query, $order) {
                // Use raw SQL to join and order by the correct column
                $query->join('delivery_order_items', 'item_arrivals.delivery_order_item_id', '=', 'delivery_order_items.id')
                    ->join('delivery_orders', 'delivery_order_items.delivery_order_id', '=', 'delivery_orders.id')
                    ->join('warehouses', 'delivery_orders.warehouse_id', '=', 'warehouses.id')
                    ->orderBy('warehouses.spk_number', $order);
            })
            ->orderColumn('item', function ($query, $order) {
                // Use raw SQL to join and order by the correct column
                $query->join('delivery_order_items', 'item_arrivals.delivery_order_item_id', '=', 'delivery_order_items.id')
                    ->join('material_request_items', 'delivery_order_items.material_request_item_id', '=', 'material_request_items.id')
                    ->join('items', 'material_request_items.item_id', '=', 'items.id')
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
        return view('arrival.create', compact('warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'items.*.received_qty' => 'required|numeric|min:0',
            'items.*.remark' => 'nullable|string',
        ], [
            'items.*.received_qty.min' => 'Qty tidak boleh lebih kecil daripada 0',
        ]);

        
        DB::transaction(function () use ($validatedData) {
            foreach ($validatedData['items'] as $itemId => $data) {
                // Update the delivered_qty in your existing table
                $item = DeliveryOrderItem::findOrFail($itemId);
                $item->received_qty = $item->received_qty + $data['received_qty'];
                $item->save();
                $warehouse = $item->warehouse;
                
                // Insert into item_arrival table
                $itemArrival = ItemArrival::create([
                    'delivery_order_item_id' => $itemId,
                    'item_id' => $item->materialRequestItem->item->id,
                    'arrived_qty' => $data['received_qty'],
                    'remark' => $data['remark'] ?? null, // Check if remark exists
                    'po_number' => $item->po_number,
                    'arrival_date' => now()
                ]);

                // Update Material request item
                $material_request_item = MaterialRequestItem::findOrFail($item->materialRequestItem->id);
                $material_request_item->received_qty = $material_request_item->received_qty + $data['received_qty'];
                $material_request_item->updated_at = now();
                $material_request_item->save();

                // Update material request status
                $material_request = MaterialRequest::findOrFail($material_request_item->mr_id);
                $hasSufficientQty = $material_request->items()->whereColumn('received_qty', '<', 'qty')->exists();
                if ($hasSufficientQty) {
                    $material_request->status_id = 3; //Partial Completed
                }else{
                    $material_request->status_id = 4; //Completed
                }
                $material_request->updated_at = now();
                $material_request->save();

                // Warehouse Items
                $warehouseItem = WarehouseItem::where('warehouse_id', $warehouse->id)->where('item_id', $item->materialRequestItem->item->id)->first();
                if($warehouseItem){
                    $warehouseItem->qty += $data['received_qty'];
                    $warehouseItem->save();
                }else{
                    WarehouseItem::create([
                        'warehouse_id' => $warehouse->id,
                        'item_id' => $item->materialRequestItem->item->id,
                        'qty' => $data['received_qty']
                    ]);
                }

                // Stock Card
                StockCard::firstOrCreate(
                    [
                        'warehouse_id'=> $warehouse->id,
                        'ref_id' => $itemArrival->id,
                        'item_id'=> $item->materialRequestItem->item->id,
                        'type'=>'in',
                    ],
                    [
                        'qty'=>$data['received_qty'],
                        'date'=> $itemArrival->arrival_date,
                    ]
                );

            }
        });

        return redirect()->back()->with('success', 'Penerimaan Barang berhasil disimpan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(ItemArrival $itemArrival)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemArrival $itemArrival)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemArrival $itemArrival)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemArrival $itemArrival)
    {
        //
    }
}
