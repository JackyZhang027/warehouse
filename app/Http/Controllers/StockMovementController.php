<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\StockMovementLine;
use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    // List all movements
    public function index()
    {
        $movements = StockMovement::with(['lines', 'warehouseFrom', 'warehouseTo'])->latest()->paginate(10);
        return view('stock_movements.index', compact('movements'));
    }

    // Show create form
    public function create()
    {
        $items = Item::all();
        $warehouses = Warehouse::all();
        return view('stock_movements.create', compact('items', 'warehouses'));
    }

    // Store new movement
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'movement_type' => 'required|in:in,out,transfer',
            'warehouse_from_id' => 'nullable|exists:warehouses,id',
            'warehouse_to_id' => 'nullable|exists:warehouses,id',
            'lines' => 'required|array',
            'lines.*.item_id' => 'required|exists:items,id',
            'lines.*.qty' => 'required|numeric',
        ]);

        $movement = StockMovement::create([
            'date' => $request->date,
            'movement_type' => $request->movement_type,
            'warehouse_from_id' => $request->warehouse_from_id,
            'warehouse_to_id' => $request->warehouse_to_id,
            'created_by' => auth()->id(),
        ]);

        foreach ($request->lines as $line) {
            $movement->lines()->create($line);
        }

        return redirect()->route('stock_movements.index')->with('success', 'Stock movement created.');
    }

    // Show single movement
    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load('lines.item', 'warehouseFrom', 'warehouseTo');
        return view('stock_movements.show', compact('stockMovement'));
    }

    // Show edit form
    public function edit(StockMovement $stockMovement)
    {
        $stockMovement->load('lines');
        $items = Item::all();
        $warehouses = Warehouse::all();
        return view('stock_movements.edit', compact('stockMovement', 'items', 'warehouses'));
    }

    // Update movement
    public function update(Request $request, StockMovement $stockMovement)
    {
        $request->validate([
            'date' => 'required|date',
            'movement_type' => 'required|in:in,out,transfer',
            'warehouse_from_id' => 'nullable|exists:warehouses,id',
            'warehouse_to_id' => 'nullable|exists:warehouses,id',
            'lines' => 'required|array',
            'lines.*.item_id' => 'required|exists:items,id',
            'lines.*.qty' => 'required|numeric',
        ]);

        $stockMovement->update($request->only(['date','movement_type','warehouse_from_id','warehouse_to_id','created_by']));

        // Delete old lines and insert new ones
        $stockMovement->lines()->delete();
        foreach ($request->lines as $line) {
            $stockMovement->lines()->create($line);
        }

        return redirect()->route('stock_movements.index')->with('success', 'Stock movement updated.');
    }

    // Delete movement
    public function destroy(StockMovement $stockMovement)
    {
        $stockMovement->delete();
        return redirect()->route('stock_movements.index')->with('success', 'Stock movement deleted.');
    }
}
