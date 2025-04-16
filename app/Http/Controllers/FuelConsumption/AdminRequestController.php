<?php

namespace App\Http\Controllers\FuelConsumption;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminRequestController extends Controller
{
    /**
     * Display the admin request page with filters and pagination.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function adminRequest(Request $request)
    {
        // Apply filters if they exist
        $query = DB::table('driver_request_table');
    
        // Filter by driver name
        if ($request->has('driver_id') && $request->driver_id) {
            $driver = DB::table('drivers_list_table')
                ->where('id', $request->driver_id)
                ->first();
    
            if ($driver) {
                $fullName = $driver->first_name . ' ' . $driver->last_name;
                $query->where('driver_name', 'like', "%$fullName%");
            }
        }
    
        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }
    
        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
    
        // Filter by division
        if ($request->has('division') && $request->division) {
            $query->where('division', $request->division);
        }
    
        // Paginate results
        $driverRequests = $query->orderBy('updated_at', 'desc')->paginate(10);
    
        // Get active drivers for the dropdown
        $drivers = DB::table('drivers_list_table')
            ->where('status', 'active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->select('id', 'first_name', 'last_name')
            ->get();
            
        // Get divisions from the divisions table
        $divisions = DB::table('divisions')
            ->orderBy('name')
            ->select('id', 'name')
            ->get();
            
        // Get types from the inventory_products table
        $types = DB::table('inventory_products')
            ->orderBy('product_name')
            ->select('id', 'product_name')
            ->get();
    
        // Return JSON response for AJAX requests
        if ($request->ajax()) {
            return view('FuelConsumption.adminRequest', [
                'driverRequests' => $driverRequests,
                'drivers' => $drivers,
                'divisions' => $divisions,
                'types' => $types,
                'activePage' => 'admin-request'
            ])->render();
        }
    
        // Return full view for non-AJAX requests
        return view('FuelConsumption.adminRequest', [
            'driverRequests' => $driverRequests,
            'drivers' => $drivers,
            'divisions' => $divisions,
            'types' => $types,
            'activePage' => 'admin-request'
        ]);
    }

    /**
     * Get a list of active drivers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDrivers()
    {
        $drivers = DB::table('drivers_list_table')
            ->where('status', 'active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->select('id', 'first_name', 'last_name')
            ->get();

        return response()->json(['drivers' => $drivers]);
    }

    /**
     * Get details of a specific request.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRequestDetails($id)
    {
        $requestDetails = DB::table('driver_request_table')
            ->where('id', $id)
            ->first();

        if (!$requestDetails) {
            return response()->json(['error' => 'Request not found'], 404);
        }

        return response()->json($requestDetails);
    }

/**
 * Update the status of a request.
 *
 * @param Request $request
 * @param int $id
 * @return \Illuminate\Http\JsonResponse
 */
public function updateRequestStatus(Request $request, $id)
{
    try {
        $validated = $request->validate([
            'status' => 'required|in:Approved,Disapproved' // Capital A and D to match JavaScript
        ]);

        $updated = DB::table('driver_request_table')
            ->where('id', $id)
            ->update(['status' => $validated['status']]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => "Request {$validated['status']} successfully"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Request not found or already updated'
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update request status: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Edit request details.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function editRequest($id)
    {
        $requestDetails = DB::table('driver_request_table')
            ->where('id', $id)
            ->first();

        if (!$requestDetails) {
            abort(404);
        }

        return view('FuelConsumption/editRequest', [
            'requestDetails' => $requestDetails
        ]);
    }

    /**
     * Update request details.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRequestDetails(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'driver_name' => 'required|string',
                'date' => 'required|date',
                'type' => 'required|string',
                'quantity' => 'required|numeric',
                'government_car_used' => 'required|string',
                'government_car_number' => 'required|string',
                'place_to_visit' => 'required|string',
                'purpose' => 'required|string',
                'requested_by' => 'required|string',
                'division' => 'required|string',
            ]);

            $updated = DB::table('driver_request_table')
                ->where('id', $id)
                ->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Request details updated successfully',
                'updated' => $updated
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update request details',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getDropdownOptions()
    {
        // Fetch active drivers
        $drivers = DB::table('drivers_list_table')
            ->where('status', 'active')
            ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) as full_name"))
            ->get();
    
        // Fetch equipment types
        $equipments = DB::table('equipment')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
        
        // Fetch units
        $units = DB::table('units')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
        
        // Fetch divisions
        $divisions = DB::table('divisions')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
            
        // Fetch inventory products for types
        $inventoryProducts = DB::table('inventory_products')
            ->select('id', 'product_name')
            ->orderBy('product_name', 'asc')
            ->get();
    
        return response()->json([
            'drivers' => $drivers,
            'equipments' => $equipments,
            'units' => $units,
            'divisions' => $divisions,
            'inventoryProducts' => $inventoryProducts,
        ]);
    }
    /**
     * Delete a single request.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
/**
 * Delete a single request.
 *
 * @param int $id
 * @return \Illuminate\Http\JsonResponse
 */
public function deleteRequest($id)
{
    try {
        $deleted = DB::table('driver_request_table')
            ->where('id', $id)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Request deleted successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Request not found or already deleted'
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete request: ' . $e->getMessage()
        ], 500);
    }
}
 
}
