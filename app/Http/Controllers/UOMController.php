<?php

namespace App\Http\Controllers;

use App\Models\UOM;
use Illuminate\Http\Request;
use DataTables;

class UOMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function __construct()
    {
        $this->middleware('permission:uom-list|uom-create|uom-edit|uom-delete', ['only' => ['index','store']]);
        $this->middleware('permission:uom-create', ['only' => ['create','store']]);
        $this->middleware('permission:uom-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:uom-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = UOM::query();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                
                $editBtn = '';
                $deleteBtn = '';
                
                if (auth()->user()->can('uom-edit')) {
                    $editBtn = ' <button class="btn btn-primary btn-sm edit-btn" data-toggle="modal" data-target="#editUomModal"
                            data-id="' . $row->id . '" data-name="' . $row->name . '"><i class="fas fa-edit"></i> </button> ';
                }
                
                if (auth()->user()->can('uom-delete')) {
                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\''. route('uoms.destroy', $row->id) .'\', \'tblUom\')"><i class="fas fa-trash-alt"></i> </button>';
                }
                return $editBtn.$deleteBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
           
        return view('uoms.index');
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

        Uom::create($request->all());

        return response()->json(['success'=>true, 'msg' => 'UOM created successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(UOM $uOM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UOM $uOM)
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

        $uom = Uom::find($id);
        $uom->update($request->all());

        return response()->json(['success'=>true, 'msg' => 'UOM updated successfully!']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        UOM::find($id)->delete();
        return response()->json(['success'=>true, 'msg' => 'UOM deleted successfully!']);
    }

}
