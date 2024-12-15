<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordRequired
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the password is already validated in the session
        if (!$request->session()->get('password_verified')) {
            // Redirect to the password input page if not validated
            return redirect()->route('password.input');
        }

        return $next($request);
    }

}
