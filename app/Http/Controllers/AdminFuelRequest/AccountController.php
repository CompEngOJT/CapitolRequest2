<?php

namespace App\Http\Controllers\AdminFuelRequest;

use App\Http\Controllers\Controller;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function showAccount()
    {
        $adminUser = AdminUser::first();
        return view('AdminFuelRequest/account', [
            'adminUser' => $adminUser,
            'activePage' => 'account', 
        ]);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:admin_users,username',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = AdminUser::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => 'Password reset successfully!']);
    }
}