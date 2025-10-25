<?php

namespace App\Http\Controllers;

use App\Models\WarehouseItem;
use Illuminate\Http\Request;

class WarehouseItemController extends Controller
{
    public function searchItems(Request $request)
    {
        $keyword = $request->input('keyword');
        $warehouseId = $request->input('warehouse_id');

        $items = WarehouseItem::with(['item', 'item.uom'])
            ->when($keyword, function ($query, $keyword) {
                return $query->whereHas('item', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%');
                });
            })
            ->when($warehouseId, function ($query, $warehouseId) {
                return $query->where('warehouse_id', $warehouseId);
            })
            ->get();

        return response()->json($items);
    }
}
