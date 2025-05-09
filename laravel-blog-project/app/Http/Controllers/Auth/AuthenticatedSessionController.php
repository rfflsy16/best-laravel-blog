<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout the user
        Auth::guard('web')->logout();

        // Invalidate the session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Forget all cookies
        $cookies = $request->cookies->all();
        $response = redirect()->route('login');
        
        foreach ($cookies as $name => $value) {
            // Exclude necessary cookies like XSRF-TOKEN
            if (!in_array($name, ['XSRF-TOKEN'])) {
                $response->withoutCookie($name);
            }
        }
        
        // Explicitly remove Laravel session cookie
        $response->withoutCookie('laravel_session');
        
        // Remove any other auth-related cookies
        $response->withoutCookie('remember_web_');
        
        return $response;
    }
}
