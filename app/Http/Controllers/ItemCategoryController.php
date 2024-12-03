<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;
use DataTables;

class ItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
     function __construct()
     {
         $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index','store']]);
         $this->middleware('permission:category-create', ['only' => ['create','store']]);
         $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:category-delete', ['only' => ['destroy']]);
     }
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = ItemCategory::query();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                
                $editBtn = '';
                $deleteBtn = '';
                
                if (auth()->user()->can('category-edit')) {
                    $editBtn = ' <button class="btn btn-primary btn-sm edit-btn" data-toggle="modal" data-target="#editCategoryModal"
                            data-id="' . $row->id . '" data-name="' . $row->name . '"><i class="fas fa-edit"></i> </button> ';
                }
                
                if (auth()->user()->can('category-delete')) {
                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\''. route('categories.destroy', $row->id) .'\', \'tblCategory\')"><i class="fas fa-trash-alt"></i> </button>';
                }
                return $editBtn.$deleteBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
           
        return view('category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ItemCategory::create($request->all());

        return response()->json(['success'=>true, 'msg' => 'Item Category created successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemCategory $ItemCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemCategory $ItemCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $ItemCategory = ItemCategory::find($id);
        $ItemCategory->update($request->all());

        return response()->json(['success'=>true, 'msg' => 'Item Category updated successfully!']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ItemCategory::find($id)->delete();
        return response()->json(['success'=>true, 'msg' => 'ItemvCategory deleted successfully!']);
    }

}
