<?php

namespace App\Http\Controllers\FuelConsumption;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminConsumptionController extends Controller
{
    /**
     * Display the inventory page with pagination.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminConsumption(Request $request)
    {
        $activePage = 'consumption';
        
        // Fetch products from the database with pagination (9 per page) and alphabetical order
        $products = DB::table('inventory_products')
            ->select('product_name', 'total_stocks', 'last_withdrawal', 'stock_remaining')
            ->orderBy('product_name', 'asc')
            ->paginate(9);
        
        // Calculate the most and least requested products
        $mostRequested = DB::table('inventory_products')
            ->orderBy('last_withdrawal', 'desc')
            ->limit(1)
            ->first();
        
        $leastRequested = DB::table('inventory_products')
            ->orderBy('last_withdrawal', 'asc')
            ->limit(1)
            ->first();
        
        $totalProducts = DB::table('inventory_products')->count();
        
        // If this is an AJAX request, only return the view content
        if ($request->ajax()) {
            // We'll return the full view but only extract needed parts in JavaScript
            return view('FuelConsumption.adminConsumption', compact('activePage', 'products', 'mostRequested', 'leastRequested', 'totalProducts'));
        }
        
        // Regular page load
        return view('FuelConsumption.adminConsumption', compact('activePage', 'products', 'mostRequested', 'leastRequested', 'totalProducts'));
    }
}