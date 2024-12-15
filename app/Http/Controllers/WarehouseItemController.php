<?php

namespace App\Http\Controllers;

use App\Models\WarehouseItem;
use Illuminate\Http\Request;

class WarehouseItemController extends Controller
{
    public function searchItems(Request $request)
    {
        $keyword = $request->input('keyword');
        
        // If a keyword is provided, filter the items
        $items = WarehouseItem::with(['item', 'item.uom'])
                    ->when($keyword, function ($query, $keyword) {
                        return $query->whereHas('item', function ($q) use ($keyword) {
                            $q->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('code', 'like', '%' . $keyword . '%');
                        });
                    })
                    ->get();

        return response()->json($items);
    }

}
