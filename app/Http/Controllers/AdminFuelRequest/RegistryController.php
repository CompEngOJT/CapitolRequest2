<?php

namespace App\Http\Controllers\AdminFuelRequest;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistryController extends Controller
{
    public function showRegistry()
    {
        return view('AdminFuelRequest/registry', ['activePage' => 'registry']);
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
    
        // Check if this is an AJAX request
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Driver added successfully!']);
        }
    
        // Regular form submission
        return redirect()->route('registry')->with('success', 'Driver added successfully!');
    }
    
    public function storeVehicle(Request $request)
    {
        // Validate the request (optional fields)
        $request->validate([
            'equipment-type' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
        ]);
    
        // Insert into equipment table if equipment-type is provided
        if ($request->has('equipment-type') && $request->input('equipment-type') !== '') {
            DB::table('equipment')->insert([
                'name' => $request->input('equipment-type'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
        // Insert into units table if unit is provided
        if ($request->has('unit') && $request->input('unit') !== '') {
            DB::table('units')->insert([
                'name' => $request->input('unit'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
        // Check if at least one field was filled
        if ($request->input('equipment-type') === '' && $request->input('unit') === '') {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Please fill in at least one field (Equipment Type or Unit).'], 422);
            }
            return redirect()->route('registry')->with('error', 'Please fill in at least one field (Equipment Type or Unit).');
        }
    
        // If it's an AJAX request, return JSON response
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Information saved successfully!']);
        }
    
        // Regular form submission
        return redirect()->route('registry')->with('success', 'Information saved successfully!');
    }
}