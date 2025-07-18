<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\AdminUser;

class AdminUserController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function showDocument()
    {
        return view('document');
    }

    public function showDashboard()
    {
        return view('dashboard');
    }

    public function showInventory()
    {
        $inventoryData = $this->getInventoryData()->getData();
        return view('inventory', compact('inventoryData'));
    }

    public function showAccount()
    {
        $adminUser = AdminUser::first();
        return view('account', compact('adminUser'));
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

    public function showRequest()
    {
        $drivers = DB::table('drivers_list_table')->orderBy('first_name', 'asc')->get();
        $vehicles = DB::table('vehicle_list_table')
            ->orderBy('equipment_type', 'asc')
            ->orderBy('unit', 'asc')
            ->get();
        return view('request', compact('drivers', 'vehicles'));
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'driver_name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'measurement' => 'required|string|max:255',
            'government_car_used' => 'required|string|max:255',
            'government_car_number' => 'required|string|max:255',
            'place_to_visit' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'requested_by' => 'required|string|max:255',
            'division' => 'required|string|max:255',
        ]);

        DB::table('driver_request_table')->insert([
            'driver_name' => $request->driver_name,
            'date' => $request->date,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'measurement' => $request->measurement,
            'government_car_used' => $request->government_car_used,
            'government_car_number' => $request->government_car_number,
            'place_to_visit' => $request->place_to_visit,
            'purpose' => $request->purpose,
            'requested_by' => $request->requested_by,
            'division' => $request->division,
            'status' => 'Under Review',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('request')->with('success', 'Request submitted successfully!');
    }

    public function showRegistry()
    {
        return view('registry');
    }

    public function storeDriver(Request $request)
    {
        $request->validate([
            'first-name' => 'required|string|max:255',
            'last-name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        DB::table('drivers_list_table')->insert([
            'first_name' => $request->input('first-name'),
            'last_name' => $request->input('last-name'),
            'position' => $request->input('position'),
            'status' => $request->input('status'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('registry')->with('success', 'Driver added successfully!');
    }

    public function storeVehicle(Request $request)
    {
        $request->validate([
            'equipment-type' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
        ]);

        DB::table('vehicle_list_table')->insert([
            'equipment_type' => $request->input('equipment-type'),
            'unit' => $request->input('unit'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('registry')->with('success', 'Vehicle added successfully!');
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

    public function getInventoryData()
    {
        $products = DB::table('total_stocks_table')->get();

        $inventoryData = [];

        foreach ($products as $product) {
            $lastWithdrawal = DB::table('driver_request_table')
                ->where('product_id', $product->id)
                ->orderBy('date', 'desc')
                ->first();

            $totalWithdrawals = DB::table('driver_request_table')
                ->where('product_id', $product->id)
                ->sum('quantity');

            $stockRemaining = $product->total_stocks - $totalWithdrawals;

            $inventoryData[] = [
                'product' => $product->product_name,
                'total_stocks' => $product->total_stocks,
                'last_withdrawal' => $lastWithdrawal ? $lastWithdrawal->quantity : 0,
                'stock_remaining' => $stockRemaining,
            ];
        }

        return response()->json($inventoryData);
    }
}