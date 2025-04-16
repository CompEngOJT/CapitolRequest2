<?php

namespace App\Http\Controllers\AdminFuelRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // If user is already logged in, redirect to dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }
        
        return view('AdminFuelRequest.login');
    }

    /**
     * Handle the login request
     */
    public function login(Request $request)
    {
        // Validate request data
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        $credentials = $request->only('username', 'password');
        
        if (Auth::guard('admin')->attempt($credentials)) {
            // Authentication passed
            $request->session()->regenerate();
            
            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'redirect' => route('dashboard')
                ]);
            }
            
            // Redirect to dashboard for normal requests
            return redirect()->intended(route('dashboard'));
        }

        // Authentication failed
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password'
            ], 401);
        }
        
        // Redirect back with error for normal requests
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password'));
    }
/**
 * Handle user logout
 */
public function logout(Request $request)
{
    Auth::guard('admin')->logout();
    
    // Invalidate session and regenerate token
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    // Clear all session data
    Session::flush();
    
    // Redirect with cache control headers
    return redirect()->route('login')
        ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
}
}