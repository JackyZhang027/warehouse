<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use DataTables;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
     function __construct()
     {
          $this->middleware('permission:warehouse-list|warehouse-create|warehouse-edit|warehouse-delete', ['only' => ['index','store']]);
          $this->middleware('permission:warehouse-create', ['only' => ['create','store']]);
          $this->middleware('permission:warehouse-edit', ['only' => ['edit','update']]);
          $this->middleware('permission:warehouse-delete', ['only' => ['destroy']]);
     }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Warehouse::query();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                
                $editBtn = '';
                $deleteBtn = '';
                
                if (auth()->user()->can('warehouse-edit')) {
                    $editBtn = '<a href="'. route('warehouse.edit', $row->id) .'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a> ';
                }
                
                if (auth()->user()->can('warehouse-delete')) {
                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\''. route('warehouse.destroy', $row->id) .'\', \'tblWarehouse\')"><i class="fas fa-trash-alt"></i> </button>';
                }
            
                
                
                return $editBtn.$deleteBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
           
        return view('warehouses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'owner' => 'required|string|max:255',
            'project' => 'required|string|max:255',
            'spk_number' => 'required|string|max:255',
            'location' => 'required|string',
            'logistic' => 'required|string|max:255',
            'supervisor' => 'required|string|max:255',
            'site_manager' => 'required|string|max:255',
            'project_manager' => 'required|string|max:255',
            'head_logistic' => 'required|string|max:255',
            'site_engineer' => 'required|string|max:255',
            'asset_controller' => 'required|string|max:255',
            'head_purchasing' => 'required|string|max:255',
            'project_management' => 'required|string|max:255',
            'branch_manager' => 'required|string|max:255',
            'sequence_format' => 'required|string|max:255',
        ]);
        
        Warehouse::create($request->all());

        return response()->json(['success'=>true, 'msg' => 'Gudang berhasil ditambahkan!']);
    }


    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', ["warehouse"=>$warehouse]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'owner' => 'required|string|max:255',
            'project' => 'required|string|max:255',
            'spk_number' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'logistic' => 'nullable|string|max:255',
            'supervisor' => 'nullable|string|max:255',
            'site_manager' => 'nullable|string|max:255',
            'project_manager' => 'nullable|string|max:255',
            'head_logistic' => 'nullable|string|max:255',
            'site_engineer' => 'required|string|max:255',
            'asset_controller' => 'required|string|max:255',
            'head_purchasing' => 'required|string|max:255',
            'project_management' => 'required|string|max:255',
            'branch_manager' => 'nullable|string|max:255',
            'sequence_format' => 'nullable|string|max:255',
        ]);

        // Find the warehouse by ID
        $warehouse = Warehouse::findOrFail($id);

        // Update the warehouse with the request data
        $warehouse->update($request->all());

        // Return a success response
        return response()->json(['success'=>true, 'msg' => 'Warehouse updated successfully!']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Warehouse::find($id)->delete();
        return response()->json(['success'=>true, 'msg' => 'Warehouse deleted successfully!']);
    }

}
