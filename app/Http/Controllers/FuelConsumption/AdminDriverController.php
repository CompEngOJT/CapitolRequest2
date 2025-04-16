<?php

namespace App\Http\Controllers\FuelConsumption;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminDriverController extends Controller
{
    /**
     * Display the driver list page.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function adminDriverList(Request $request)
    {
        $activePage = 'drivers-list';
        $perPage = 10; // Number of items per page
        $searchTerm = $request->query('search');
        
        // Fetch all drivers
        $query = DB::table('drivers_list_table')
            ->select('id', 'first_name', 'last_name', 'position');
            
        // Apply search filter if search term exists
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('position', 'like', '%' . $searchTerm . '%')
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }
        
        $allDrivers = $query->get()
            ->unique(function ($item) {
                return $item->first_name . $item->last_name;
            })
            ->sortBy(function ($driver) {
                return $driver->first_name . ' ' . $driver->last_name;
            })
            ->values(); // Reset keys for proper indexing
        
        // Manual pagination
        $currentPage = $request->query('page', 1);
        $pagedData = $allDrivers->forPage($currentPage, $perPage);
        
        $drivers = new LengthAwarePaginator(
            $pagedData,
            $allDrivers->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Check if this is an AJAX request
        if ($request->ajax()) {
            return view('FuelConsumption.adminDriver', compact('activePage', 'drivers'))->render();
        }
        
        return view('FuelConsumption.adminDriver', compact('activePage', 'drivers'));
    }

    /**
     * Store a newly created driver in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
        ]);

        try {
            // Insert the new driver into the database
            $driverId = DB::table('drivers_list_table')->insertGetId([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'position' => $validatedData['position'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // If it's an AJAX request, return JSON response
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Driver added successfully!',
                    'driver' => [
                        'id' => $driverId,
                        'first_name' => $validatedData['first_name'],
                        'last_name' => $validatedData['last_name'],
                        'position' => $validatedData['position'],
                    ]
                ]);
            }

            // If it's a regular form submission, redirect with success message
            return redirect()->route('admin.driver.list')
                ->with('success', 'Driver added successfully!');

        } catch (\Exception $e) {
            // If it's an AJAX request, return JSON error response
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to add driver: ' . $e->getMessage()
                ], 500);
            }

            // If it's a regular form submission, redirect with error message
            return redirect()->route('admin.driver.list')
                ->with('error', 'Failed to add driver: ' . $e->getMessage());
        }
    }

/**
 * Delete a driver and optionally all duplicate drivers from storage.
 *
 * @param  int  $id
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function deleteDriver($id, Request $request)
{
    try {
        // Check if driver exists
        $driver = DB::table('drivers_list_table')->where('id', $id)->first();
        
        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found'
            ], 404);
        }
        
        $duplicatesRemoved = 0;
        
        if ($request->query('remove_duplicates') === 'true') {
            $firstName = $driver->first_name;
            $lastName = $driver->last_name;
            
            // Delete all drivers with the same name except the current one
            $duplicatesRemoved = DB::table('drivers_list_table')
                ->where('first_name', $firstName)
                ->where('last_name', $lastName)
                ->where('id', '!=', $id)
                ->delete();
                
        }
        
        // Delete the original driver
        DB::table('drivers_list_table')->where('id', $id)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Driver deleted successfully',
            'duplicatesRemoved' => $duplicatesRemoved
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete driver: ' . $e->getMessage()
        ], 500);
    }
}
/**
 * Get driver consumption data
 *
 * @param int $id Driver ID
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function getDriverConsumption($id, Request $request)
{
    try {
        // Get the driver
        $driver = DB::table('drivers_list_table')
            ->select('id', 'first_name', 'last_name')
            ->where('id', $id)
            ->first();
            
        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found'
            ], 404);
        }
        
        $driverFullName = $driver->first_name . ' ' . $driver->last_name;
        
        // First, get all products from inventory
        $allProducts = DB::table('inventory_products')
            ->select('id', 'product_name')
            ->orderBy('product_name')
            ->get();
            
        // Get consumption data for each product
        $consumptionData = [];
        foreach ($allProducts as $product) {
            // Check if this product has consumption data
            $consumption = DB::table('driver_request_table')
                ->select(DB::raw('SUM(quantity) as total_consumption'))
                ->where('driver_name', $driverFullName)
                ->where('type', $product->product_name) // Assuming 'type' corresponds to product name
                ->where('status', 'approved');
                
            // Apply date filters if provided
            if ($request->has('start_date') && !empty($request->start_date)) {
                $consumption->whereDate('created_at', '>=', $request->start_date);
            }
            
            if ($request->has('end_date') && !empty($request->end_date)) {
                $consumption->whereDate('created_at', '<=', $request->end_date);
            }
            
            $consumptionAmount = $consumption->first();
            
            $consumptionData[] = [
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'total_consumption' => $consumptionAmount ? $consumptionAmount->total_consumption : 0
            ];
        }
        
        // Get all unique dates for the dropdown
        $dates = DB::table('driver_request_table')
            ->select(DB::raw('DATE(created_at) as request_date'))
            ->where('driver_name', $driverFullName)
            ->where('status', 'approved')
            ->groupBy('request_date')
            ->orderBy('request_date', 'desc')
            ->pluck('request_date')
            ->toArray();
            
        return response()->json([
            'success' => true,
            'driver' => [
                'id' => $driver->id,
                'name' => $driverFullName
            ],
            'consumption' => $consumptionData,
            'dates' => $dates
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch consumption data: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Get product request details for a specific driver
 *
 * @param int $driverId Driver ID
 * @param int $productId Product ID
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function getProductRequests($driverId, $productId, Request $request)
{
    try {
        // Get the driver
        $driver = DB::table('drivers_list_table')
            ->select('id', 'first_name', 'last_name')
            ->where('id', $driverId)
            ->first();
            
        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found'
            ], 404);
        }
        
        $driverFullName = $driver->first_name . ' ' . $driver->last_name;
        
        // Get the product
        $product = DB::table('inventory_products')
            ->select('id', 'product_name')
            ->where('id', $productId)
            ->first();
            
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        // Get all requests for this product by this driver
        $query = DB::table('driver_request_table')
            ->select('id', 'quantity', 'created_at', 'status')
            ->where('driver_name', $driverFullName)
            ->where('type', $product->product_name)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc');
            
        // Apply date filters if provided
        if ($request->has('start_date') && !empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && !empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        // Get all requests for pagination
        $allRequests = $query->get();
        
        // Manual pagination
        $page = $request->input('page', 1);
        $perPage = 4; // 5 items per page as requested
        $offset = ($page - 1) * $perPage;
        
        $paginatedRequests = $allRequests->slice($offset, $perPage);
        
        $requestsData = $paginatedRequests->map(function($item) {
            return [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'date' => date('Y-m-d', strtotime($item->created_at)),
                'time' => date('H:i:s', strtotime($item->created_at)),
                'status' => $item->status
            ];
        });
        
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->product_name
            ],
            'requests' => $requestsData,
            'pagination' => [
                'total' => $allRequests->count(),
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($allRequests->count() / $perPage)
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to fetch product requests: ' . $e->getMessage()
        ], 500);
    }
}
/**
 * Show the form for editing the specified driver.
 *
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function edit($id)
{
    try {
        $driver = DB::table('drivers_list_table')
            ->where('id', $id)
            ->first();
            
        if (!$driver) {
            return response()->json(['message' => 'Driver not found'], 404);
        }
        
        return response()->json($driver);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error fetching driver: ' . $e->getMessage()], 500);
    }
}

/**
 * Update the specified driver in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function update(Request $request, $id)
{
    try {
        // Validate request
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        
        // Check if driver exists
        $driver = DB::table('drivers_list_table')
            ->where('id', $id)
            ->first();
            
        if (!$driver) {
            return response()->json(['message' => 'Driver not found'], 404);
        }
        
        // Update driver data
        DB::table('drivers_list_table')
            ->where('id', $id)
            ->update([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'position' => $validatedData['position'],
                'status' => $validatedData['status'],
                'updated_at' => now(),
            ]);
        
        // Get the updated driver data
        $updatedDriver = DB::table('drivers_list_table')
            ->where('id', $id)
            ->first();
        
        return response()->json([
            'message' => 'Driver updated successfully',
            'driver' => $updatedDriver
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to update driver: ' . $e->getMessage()], 500);
    }
}
}