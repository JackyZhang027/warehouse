<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::query();            

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $editBtn = '';
                $deleteBtn = '';
                $editBtn = '<a href="'. route('permissions.edit', $row->id) .'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a> ';
                $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\''. route('permissions.destroy', $row->id) .'\', \'tblPermissions\')"><i class="fas fa-trash-alt"></i> </button>';
                return $editBtn.$deleteBtn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
        }
           
        return view('permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'guard_name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'model_type' => 'required|string|max:255',
        ]);

        Permission::create($validated);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'guard_name' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'model_type' => 'required|string|max:255',
        ]);

        $permission->update($validated);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        try {
            // Check if the permission is associated with any roles or users
            if ($permission->roles()->count() > 0 || $permission->users()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Cannot delete permission: It is assigned to roles or users.'
                ]);
            }
    
            // Proceed to delete the permission
            $permission->delete();
    
            return response()->json([
                'success' => true,
                'msg' => 'Permission deleted successfully!'
            ]);
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'success' => false,
                'msg' => 'Failed to delete permission: ' . $e->getMessage()
            ]);
        }
    }

}
