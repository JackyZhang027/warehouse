<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\UOM;
use Illuminate\Http\Request;
use DataTables;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Item::query();
            $data->with(['category:id,name', 'uom:id,name']);
            

            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('category_id', function ($row) {
                return $row->category->name;
            })
            ->editColumn('uom_id', function ($row) {
                return $row->uom->name;
            })
            ->addColumn('action', function($row){
                $editBtn = '';
                $deleteBtn = '';
                
                if (auth()->user()->can('item-edit')) {
                    $editBtn = '<a href="'. route('items.edit', $row->id) .'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a> ';
                }
                
                if (auth()->user()->can('item-delete')) {
                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\''. route('items.destroy', $row->id) .'\', \'tblItem\')"><i class="fas fa-trash-alt"></i> </button>';
                }
                return $editBtn.$deleteBtn;
            })
            ->filterColumn('category_id', function($query, $keyword) {
                $query->whereHas('category', function($q) use ($keyword) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('uom_id', function($query, $keyword) {
                $query->whereHas('uom', function($q) use ($keyword) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%$keyword%"]);
                });
            })
            ->orderColumn('category_id', function ($query, $order) {
                $query->orderBy('item_categories.name', $order == 'desc' ? 'asc' : 'desc');
            })
            ->orderColumn('uom_id', function ($query, $order) {
                $query->orderBy('uom.name', $order == 'desc' ? 'asc' : 'desc');
            })
            ->rawColumns(['action'])
            ->make(true);
        }
           
        return view('items.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $uom = UOM::all();
        $categories = ItemCategory::all();
        return view('items.create', ['uoms'=>$uom, 'categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:items,code',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:item_categories,id',
            'uom_id' => 'required|exists:uom,id',
        ]);

        Item::create($request->all());

        return response()->json(['success'=>true, 'msg' => 'Item created successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $uom = UOM::all();
        $categories = ItemCategory::all();
        return view('items.edit', ['item'=>$item, 'uoms'=>$uom, 'categories'=>$categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'code' => 'required|string|max:255|unique:items,code,'.$id,
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'category_id' => 'required|exists:item_categories,id',
            'uom_id' => 'required|exists:uom,id',
        ]);

        // Find the warehouse by ID
        $item = Item::findOrFail($id);

        // Update the warehouse with the request data
        $item->update($request->all());

        // Return a success response
        return response()->json(['success'=>true, 'msg' => 'Item updated successfully!']);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Item::find($id)->delete();
        return response()->json(['success'=>true, 'msg' => 'Item deleted successfully!']);
    }
}
