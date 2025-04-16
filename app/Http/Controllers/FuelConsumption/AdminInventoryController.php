<?php

namespace App\Http\Controllers\FuelConsumption;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminInventoryController extends Controller
{
    /**
     * Display the inventory page.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminInventory(Request $request)
    {
        $activePage = 'inventory';
        
        // Get the search query from the request
        $search = $request->input('search');
        
        // Start query builder
        $query = DB::table('inventory_products');
        
        // Apply search filter if provided
        if ($search) {
            $query->where('product_name', 'like', '%' . $search . '%');
        }
        
        // Get all inventory products with pagination
        $inventoryProducts = $query->paginate(10);
        
        // Loop through each product to calculate last withdrawal and stock remaining
        foreach ($inventoryProducts as $product) {
            // Get the most recent approved request for this product
            $lastWithdrawal = DB::table('driver_request_table')
                ->where('status', 'approved') // Filter by approved requests
                ->where('type', $product->product_name) // Match the product type
                ->orderBy('created_at', 'desc') // Order by creation date, most recent first
                ->first(); // Get only the most recent one
            
            // Set the last withdrawal amount (quantity from the most recent approved request)
            $lastWithdrawalAmount = $lastWithdrawal ? $lastWithdrawal->quantity : 0;
            
            // Get the sum of quantities from all approved requests for this product (for stock remaining)
            $totalWithdrawalAmount = DB::table('driver_request_table')
                ->where('status', 'approved') // Filter by approved requests
                ->where('type', $product->product_name) // Match the product type
                ->sum('quantity'); // Sum the quantities
            
            // Calculate the stock remaining
            $stockRemaining = $product->total_stocks - $totalWithdrawalAmount;
            
            // Update the product in the database
            DB::table('inventory_products')
                ->where('id', $product->id)
                ->update([
                    'last_withdrawal' => $lastWithdrawalAmount,
                    'stock_remaining' => $stockRemaining
                ]);
        }
        
        // Get the updated inventory products with pagination
        if ($search) {
            // If searching, maintain the search parameter in pagination links
            $inventoryProducts = $query->paginate(10)->appends(['search' => $search]);
        } else {
            // Regular pagination
            $inventoryProducts = $query->paginate(10);
        }
        
        // Check if it's an AJAX request
        if ($request->ajax()) {
            return view('FuelConsumption.adminInventory', compact('activePage', 'inventoryProducts'))->render();
        }
        
        return view('FuelConsumption.adminInventory', compact('activePage', 'inventoryProducts'));
    }
    /**
 * Add a new product to inventory.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function addProduct(Request $request)
{
    // Validate the input
    $request->validate([
        'product_name' => 'required|string|max:255',
        'total_stocks' => 'required|numeric|min:0',
    ]);

    // Insert the new product into the database
    DB::table('inventory_products')->insert([
        'product_name' => $request->product_name,
        'total_stocks' => $request->total_stocks,
        'last_withdrawal' => 0,
        'stock_remaining' => $request->total_stocks,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Product added successfully!');
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
        $perPage = 10; // Increased per page for better usability
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

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
        $query = DB::table('driver_request_table')
            ->where('type', $productName)
            ->where('status', 'approved');
        
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
        // This is important for correct stock calculation
        $allRequests = $query->orderBy('created_at', 'asc')->get();

        $totalItems = count($allRequests);
        $totalPages = ceil($totalItems / $perPage);

        // Find the most recent withdrawal
        $lastWithdrawal = $allRequests->last(); // Get the last one since we're ordering by ASC
        $lastWithdrawalAmount = $lastWithdrawal ? $lastWithdrawal->quantity : 0;

        // Calculate running stock balance for each request
        // We need to get the starting stock before the first request in our filtered set
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

        $totalWithdrawals = array_sum(array_column($requestsWithStock, 'quantity'));

        return response()->json([
            'success' => true,
            'requests' => $processedRequests,
            'current_stock' => $currentProduct->stock_remaining,
            'total_withdrawals' => $totalWithdrawals,
            'last_withdrawal' => $lastWithdrawalAmount,
            'pagination' => [
                'current_page' => (int)$page,
                'per_page' => $perPage,
                'total_items' => $totalItems,
                'total_pages' => $totalPages
            ]
        ]);
    } catch (\Exception $e) {
        // Return detailed error information
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}
/**
 * Delete a product from inventory.
 *
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */
public function deleteProduct($id)
{
    try {
        $deleted = DB::table('inventory_products')
            ->where('id', $id)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product not found or already deleted'
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete product: ' . $e->getMessage()
        ], 500);
    }
}
public function getProducts()
{
    $products = DB::table('inventory_products')
        ->select('id', 'product_name')
        ->orderBy('product_name')
        ->get();
        
    return response()->json($products);
}

public function getProductStock($id)
{
    $product = DB::table('inventory_products')
        ->select('stock_remaining') // Removed total_stocks field
        ->where('id', $id)
        ->first();
        
    if (!$product) {
        return response()->json(['error' => 'Product not found'], 404);
    }
    
    return response()->json($product);
}

public function addStock(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:inventory_products,id',
        'stock_amount' => 'required|integer|min:1'
    ]);
    
    $productId = $validated['product_id'];
    $stockAmount = $validated['stock_amount'];
    
    try {
        DB::beginTransaction();
        
        $product = DB::table('inventory_products')
            ->where('id', $productId)
            ->lockForUpdate()
            ->first();
            
        if (!$product) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        
        $newStockRemaining = $product->stock_remaining + $stockAmount;

        
        DB::table('inventory_products')
            ->where('id', $productId)
            ->update([
                'stock_remaining' => $newStockRemaining,

            ]);
            
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Stock added successfully',
            'new_stock_remaining' => $newStockRemaining,
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error adding stock: ' . $e->getMessage()
        ], 500);
    }
}
}
