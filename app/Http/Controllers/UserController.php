<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DataTables;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     function __construct()
     {
          $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
          $this->middleware('permission:user-create', ['only' => ['create','store']]);
          $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
          $this->middleware('permission:user-delete', ['only' => ['destroy']]);
     }
    
     public function index(Request $request)
     {
         if ($request->ajax()) {
 
             $data = User::query();
 
             return Datatables::of($data)
                ->addIndexColumn()
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    
                    $editBtn = '';
                    $deleteBtn = '';
                    
                    if (auth()->user()->can('user-edit')) {
                        $editBtn = '<a href="'. route('users.edit', $row->id) .'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a> ';
                    }
                    
                    if (auth()->user()->can('user-delete')) {
                        $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\''. route('users.destroy', $row->id) .'\', \'tblUser\')"><i class="fas fa-trash-alt"></i> </button>';
                        
                    }
                    return $editBtn.$deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
         }
           
         return view('users.index');
     }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::pluck('name','name')->all();
        
        $warehouses = Warehouse::all()->mapWithKeys(function ($item) {
            return [$item->id => $item->spk_number . ' - ' . $item->project];
        })->all();

        return view('users.create',compact('roles', 'warehouses'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required|array',
            'warehouses' => 'required|array'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        DB::beginTransaction();
        try {
            $user = User::create($input);
            $user->assignRole($request->input('roles'));

            // Prepare the warehouses with pivot values for created_at and updated_at
            $warehouses = collect($request->input('warehouses'))
                ->mapWithKeys(fn($id) => [$id => ['created_at' => now(), 'updated_at' => now()]])
                ->toArray();

            $user->warehouses()->sync($warehouses);

            DB::commit();

            return redirect()->route('users.index')
                            ->with('success', 'User Berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('users.index')
                            ->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $warehouses = Warehouse::all()->mapWithKeys(function ($item) {
            return [$item->id => $item->spk_number . ' - ' . $item->project];
        })->all();
        $userWarehouse = $user->warehouses->pluck('spk_number','id')->all();
    
        return view('users.edit',compact('user','roles','userRole', 'warehouses', 'userWarehouse'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
            'warehouses' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
        $user->warehouses()->syncWithPivotValues($request->input('warehouses'), ['updated_at' => now()]);
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id); // throws 404 if not found

            $user->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'User deleted successfully!'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'msg' => 'User not found.'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Delete user failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'msg' => 'Failed to delete the user. Please try again later.'
            ], 500);
        }
    }

}

