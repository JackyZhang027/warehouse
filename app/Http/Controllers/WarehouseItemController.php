<?php

namespace App\Http\Controllers;

use App\Models\WarehouseItem;
use Illuminate\Http\Request;

class WarehouseItemController extends Controller
{
    public function searchItems(Request $request)
    {
        $keyword = $request->input('keyword');
        
        $items = WarehouseItem::with(['item', 'item.uom'])
                    ->whereHas('item', function($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('code', 'like', '%' . $keyword . '%');
                    })
                    ->get();

        return response()->json($items);
    }


}
