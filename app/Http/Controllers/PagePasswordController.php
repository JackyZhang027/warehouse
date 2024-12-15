<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagePasswordController extends Controller
{
    public function show()
    {
        return view('permissions.password-input');
    }

    public function validatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        // Replace 'your-password' with the actual password to validate
        if ($request->password === 'BatamTheBest123') {
            // Store password verification in the session
            $request->session()->put('password_verified', true);

            return redirect()->route('permissions.index'); // Redirect to the intended route
        }

        return back()->withErrors(['password' => 'The password is incorrect.']);
    }

}
