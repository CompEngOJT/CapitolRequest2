<?php

namespace App\Http\Controllers\AdminFuelRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    public function showRequest()
    {
        // Fetch all data from tables
        $driversRaw = DB::table('drivers_list_table')->orderBy('first_name', 'asc')->get();
        $equipmentsRaw = DB::table('equipment')->orderBy('name', 'asc')->get();
        $unitsRaw = DB::table('units')->orderBy('name', 'asc')->get();
        $divisionsRaw = DB::table('divisions')->orderBy('name', 'asc')->get();
        $inventoryProductsRaw = DB::table('inventory_products')->orderBy('product_name', 'asc')->get();
        
        
        $drivers = collect($driversRaw)->unique(function ($item) {
            return $item->first_name . ' ' . $item->last_name;
        })->values();
        
        $equipments = collect($equipmentsRaw)->unique('name')->values();
        $units = collect($unitsRaw)->unique('name')->values();
        $divisions = collect($divisionsRaw)->unique('name')->values();
        $inventoryProducts = collect($inventoryProductsRaw)->unique('product_name')->values();
    
        // Pass the deduplicated data to the view
        return view('AdminFuelRequest/request', [
            'drivers' => $drivers,
            'equipments' => $equipments,
            'units' => $units,
            'sections' => $divisions,
            'inventoryProducts' => $inventoryProducts,
            'activePage' => 'document',
        ]);
    }

    public function storeRequest(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'driver_name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'government_car_used' => 'required|string|max:255',
            'government_car_number' => 'required|string|max:255',
            'place_to_visit' => 'required|string|max:255',
            'purpose' => 'required|string|max:255',
            'requested_by' => 'required|string|max:255',
            'division' => 'required|string|max:255',
        ]);

        // If validation fails, return JSON response for AJAX requests
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Insert the request into the database
            DB::table('driver_request_table')->insert([
                'driver_name' => $request->driver_name,
                'date' => $request->date,
                'type' => $request->type,
                'quantity' => $request->quantity,
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

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Request submitted successfully!'
                ]);
            }
            
            // Regular redirect for non-AJAX requests
            return redirect()->route('request')->with('success', 'Request submitted successfully!');
            
        } catch (\Exception $e) {
            // Handle database errors
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to submit request. Please try again.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to submit request. Please try again.');
        }
    }
}