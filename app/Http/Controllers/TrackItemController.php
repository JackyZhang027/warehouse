<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\Item;
use App\Models\WarehouseItem;


class TrackItemController extends Controller
{
    public function index(Request $request)
    {
        $warehouse_id = $request->input('warehouse_id');
        $search_category = $request->input('search_category');
        $filter = $request->input('filter');

        $user = auth()->user();
        $warehouses = [];

        if ($user) {
            if ($user->hasRole('Super Admin')) {
                $warehouses = Warehouse::all()->pluck('spk_number', 'id')->map(fn($spk, $id) => "$spk - " . Warehouse::find($id)->project)->toArray();
            } else {
                $warehouses = $user->warehouses->pluck('spk_number', 'id')->map(fn($spk, $id) => "$spk - " . $user->warehouses->find($id)->project)->toArray();
            }
        }

        $datas = collect(); // Ensure it's always iterable
        $stocks = collect(); // Ensure it's always iterable

        if ($filter) {
            $datas = MaterialRequest::with(['items', 'items.item', 'warehouse'])
                ->whereHas('items.item', function ($query) use ($search_category, $filter) {
                    $query->where($search_category === 'code' ? 'code' : 'name', 'like', "%{$filter}%");
                })
                ->when($warehouse_id, fn($query) => $query->where('warehouse_id', $warehouse_id))
                ->get();

            $stocks = WarehouseItem::with(['item', 'warehouse']) // eager load item details
                ->whereHas('item', function ($query) use ($search_category, $filter) {
                    $query->where($search_category === 'code' ? 'code' : 'name', 'like', "%{$filter}%");
                })
                ->selectRaw('item_id, warehouse_id, SUM(qty) as total_qty')
                ->groupBy('item_id', 'warehouse_id')
                ->get();
                
        }

        return view('search.track_item', compact('warehouses', 'datas', 'stocks'));
    }

}
