<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use DataTables;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Announcement::query();            

            return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('description', function ($row) {
                return $row->description;
            })
            ->addColumn('action', function($row){
                $editBtn = '';
                $deleteBtn = '';
                
                if (auth()->user()->can('announcement-edit')) {
                    $editBtn = '<a href="'. route('announcement.edit', $row->id) .'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> </a> ';
                }
                
                if (auth()->user()->can('announcement-delete')) {
                    $deleteBtn = '<button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete(\''. route('announcement.destroy', $row->id) .'\', \'tblAnnouncement\')"><i class="fas fa-trash-alt"></i> </button>';
                }
                return $editBtn.$deleteBtn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
        }
           
        return view('announcement.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('announcement.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'published' => 'required|boolean',
            'date' => 'required|date',
            'expire_date' => 'required|date',
            'description' => 'required|string',
        ]);

        // Use mass assignment to create the new announcement
        Announcement::create($validated);

        // Redirect with success message
        return redirect()->route('announcement.index')->with('success', 'Pengumuman berhasil dibuat');
    }



    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        // Return the view with the existing delivery order and other necessary data
        return view('announcement.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'published' => 'required|boolean',
            'expire_date' => 'required|date',
            'description' => 'required|string',
        ]);

        // Find the announcement by ID
        $announcement = Announcement::findOrFail($id);

        // Update the announcement data
        $announcement->update([
            'title' => $request->input('title'),
            'date' => $request->input('date'),
            'published' => $request->input('published'),
            'expire_date' => $request->input('expire_date'),
            'description' => $request->input('description'),
        ]);

        // Redirect with success message
        return redirect()->route('announcement.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the announcement by ID
        $announcement = Announcement::findOrFail($id);

        // Delete the announcement
        $announcement->delete();

        // Redirect with success message
        return response()->json(['success'=>true, 'msg' => 'Pengumuman berhasil dihapus!']);
    }

}
