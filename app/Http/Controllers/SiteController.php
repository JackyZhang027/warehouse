<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $site = Site::first();
        return view('site.index', compact('site'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string',
            'logo_path' => 'nullable|image',
            'favicon_path' => 'nullable|image',
        ]);

        if ($request->hasFile('logo_path')) {
            $data['logo_path'] = $request->file('logo_path')->store('logo', 'public');
        }

        if ($request->hasFile('favicon_path')) {
            // Generate a unique name for the favicon file
            $favicon = $request->file('favicon_path');
            $faviconName = 'favicon.' . $favicon->getClientOriginalExtension();
            
            // Move the uploaded favicon to the public/favicons/ directory
            $favicon->move(public_path('favicons'), $faviconName);
    
            // Store the relative path of the favicon
            $data['favicon_path'] = 'favicons/' . $faviconName;
        }

        Site::create($data);
        Cache::forget('site_logo');

        return redirect()->route('sites.index')->with('success', 'Informasi Perusahaan berhasil diubah');
    }

    public function update(Request $request, Site $site)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string',
            'logo_path' => 'nullable|image',
            'favicon_path' => 'nullable|image',
        ]);

        if ($request->hasFile('logo_path')) {
            if ($site->logo_path) {
                Storage::disk('public')->delete($site->logo_path);
            }
            $data['logo_path'] = $request->file('logo_path')->store('logo', 'public');
        }

        if ($request->hasFile('favicon_path')) {
            // Store the favicon directly in the public/favicons/ directory
            $favicon = $request->file('favicon_path');
            $faviconName = 'favicon-32x32.png.'. $favicon->getClientOriginalExtension();
            $favicon->move(public_path('favicons'), $faviconName); // Moves the favicon to public/favicons/
    
            $data['favicon_path'] = 'favicons/' . $faviconName; // Save the relative path
        }
    

        $site->update($data);

        Cache::forget('site_logo');
        return redirect()->route('sites.index')->with('success', 'Informasi Perusahaan berhasil diubah.');
    }


    
}
