<?php

namespace App\Http\Controllers\AdminFuelRequest;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class AdminUserController extends Controller
{
    public function showLogin()
    {
        return view('AdminFuelRequest/login');
    }


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $admin = DB::table('admin_users')->where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            session(['admin_logged_in' => true]);
            return redirect('/dashboard');
        } else {
            return back()->withErrors(['login' => 'Invalid username or password']);
        }
    }

    public function logout()
    {
        session()->forget('admin_logged_in');
        return redirect('/login');
    }

}