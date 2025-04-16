<?php

namespace App\Http\Controllers\AdminFuelRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class InventoryController extends Controller
{
   /**
     * Display the inventory page with data from the database.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function showInventory(Request $request)
    {
        // Fetch data from the inventory_products table
        $products = DB::table('inventory_products')
            ->select(
                'product_name as product',
                'total_stocks as totalStocks',
                'last_withdrawal as lastWithdrawal',
                'stock_remaining as stockRemaining'
            )
            ->orderBy('product_name');
        
        // Use Laravel's built-in pagination
        $stocks = $products->paginate(10);
        
        // Fetch ALL products for the dropdown, regardless of pagination
        $allProducts = DB::table('inventory_products')
            ->select('product_name as product')
            ->orderBy('product_name')
            ->get();
        
        // Check if this is an AJAX request
        if ($request->ajax()) {
            // Return only the table content for AJAX requests
            return view('AdminFuelRequest.partials.inventory_table', [
                'stocks' => $stocks
            ]);
        }
        
        // Return the full page for non-AJAX requests
        return view('AdminFuelRequest.inventory', [
            'stocks' => $stocks,
            'allProducts' => $allProducts, // Pass all products for the dropdown
            'activePage' => 'inventory',
        ]);
    }

    /**
     * Add stock to an existing product
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addStock(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'product' => 'required|string|exists:inventory_products,product_name',
                'quantity' => 'required|integer|min:1',
                'notes' => 'nullable|string|max:255'
            ]);

            // Get the product
            $product = DB::table('inventory_products')
                ->where('product_name', $validated['product'])
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Update the product stock
            $newTotalStocks = $product->total_stocks + $validated['quantity'];
            $newStockRemaining = $product->stock_remaining + $validated['quantity'];

            DB::table('inventory_products')
                ->where('product_name', $validated['product'])
                ->update([
                    'total_stocks' => $newTotalStocks,
                    'stock_remaining' => $newStockRemaining,
                    'updated_at' => Carbon::now()
                ]);


            return response()->json([
                'success' => true,
                'message' => 'Stock added successfully',
                'new_total' => $newTotalStocks,
                'new_remaining' => $newStockRemaining
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getWithdrawalDetails(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'product_name' => 'required|string|max:255',
                'page' => 'sometimes|integer|min:1',
                'start_date' => 'sometimes|date|nullable',
                'end_date' => 'sometimes|date|nullable',
            ]);
    
            $productName = $request->input('product_name');
            $page = $request->input('page', 1);
            $perPage = 10;
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
    
            // Log request data for debugging
            ([
                'product' => $productName,
                'page' => $page,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
    
            // Get the current product
            $currentProduct = DB::table('inventory_products')
                ->where('product_name', $productName)
                ->first();
    
            if (!$currentProduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }
    
            // Start building the query
            $query = DB::table('driver_request_table')  // Using the correct table name
                ->where('type', $productName)           // Using 'type' instead of 'product_name'
                ->where('status', 'approved');          // Only show approved requests
            
            // Apply date filters if provided
            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }
            
            if ($endDate) {
                // Add one day to end date to include the end date fully
                $endDatePlusOne = date('Y-m-d', strtotime($endDate . ' +1 day'));
                $query->whereDate('created_at', '<', $endDatePlusOne);
            }
    
            // Get all withdrawal requests ordered by created_at in ASCENDING order
            $allRequests = $query->orderBy('created_at', 'asc')->get();
    
            $totalItems = count($allRequests);
            $totalPages = ceil($totalItems / $perPage);
    
            // Calculate running stock balance for each request
            $initialStock = $currentProduct->total_stocks;
            
            if ($startDate && $allRequests->count() > 0) {
                // Count withdrawals before the start date to get the correct initial stock
                $withdrawalsBeforeStartDate = DB::table('driver_request_table')
                    ->where('type', $productName)
                    ->where('status', 'approved')
                    ->whereDate('created_at', '<', $startDate)
                    ->sum('quantity');
                    
                $initialStock = $currentProduct->total_stocks - $withdrawalsBeforeStartDate;
            }
            
            $runningStock = $initialStock;
            $requestsWithStock = [];
    
            foreach ($allRequests as $req) {
                $stockBefore = $runningStock;
                $stockAfter = $runningStock - $req->quantity;
                $runningStock = $stockAfter;
    
                $requestsWithStock[] = [
                    'driver_name' => $req->driver_name,
                    'government_car_number' => $req->government_car_number,
                    'quantity' => $req->quantity,
                    'created_at' => $req->created_at,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter
                ];
            }
    
            // Reverse the array to get descending order for display (newest first)
            $requestsWithStock = array_reverse($requestsWithStock);
    
            // Get paginated requests
            $start = ($page - 1) * $perPage;
            $processedRequests = array_slice($requestsWithStock, $start, $perPage);
    
            // Format dates for display
            foreach ($processedRequests as &$req) {
                $req['created_at'] = date('M d, Y H:i', strtotime($req['created_at']));
            }
    
            return response()->json([
                'success' => true,
                'requests' => $processedRequests,
                'pagination' => [
                    'total' => $totalItems,
                    'per_page' => $perPage,
                    'current_page' => (int)$page,
                    'total_pages' => $totalPages
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }
    /**
 * Add a new product to inventory
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function addProduct(Request $request)
{
    try {
        // Validate the request
        $validated = $request->validate([
            'product' => 'required|string|max:255|unique:inventory_products,product_name',
            'initialStock' => 'required|integer|min:0'
        ]);

        // Insert the new product
        DB::table('inventory_products')->insert([
            'product_name' => $validated['product'],
            'total_stocks' => $validated['initialStock'],
            'stock_remaining' => $validated['initialStock'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added successfully'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => $e->validator->errors()->first()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to add product: ' . $e->getMessage()
        ], 500);
    }
}
/**
 * Delete a product from inventory
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function deleteProduct(Request $request)
{
    try {
        // Validate the request
        $validated = $request->validate([
            'product' => 'required|string|exists:inventory_products,product_name',
        ]);

        // Check if there are any pending requests for this product
        $pendingRequests = DB::table('driver_request_table')
            ->where('type', $validated['product'])
            ->where('status', 'pending')
            ->count();

        if ($pendingRequests > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete product with pending requests'
            ], 400);
        }

        // Delete the product
        $deleted = DB::table('inventory_products')
            ->where('product_name', $validated['product'])
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product'
            ], 500);
        }

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
}
}