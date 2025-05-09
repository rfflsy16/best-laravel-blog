<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Double-check that user is authenticated
        if (!Auth::check() || Auth::user() === null) {
            // If using AJAX, return 401
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            
            // Clear any potentially remaining session data
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Redirect to login
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}
