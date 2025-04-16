<?php

namespace App\Http\Controllers\AdminFuelRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    // Updated showHistory method to fetch dynamic filter options
    public function showHistory(Request $request)
    {
        // Get list of drivers (without duplicates)
        $drivers = DB::table('drivers_list_table')
            ->select(DB::raw("CONCAT(first_name, ' ', last_name) as full_name"))
            ->distinct()
            ->orderBy('first_name', 'asc')
            ->get();

        // Get unique product names for type dropdown
        $productTypes = DB::table('inventory_products')
            ->select('product_name as name')
            ->distinct()
            ->orderBy('product_name', 'asc')
            ->get();

        // Get unique division names for division dropdown
        $divisions = DB::table('divisions')
            ->select('name')
            ->distinct()
            ->orderBy('name', 'asc')
            ->get();

        // Rest of your method remains the same
        $driverName = $request->input('driver_name');
        $type = $request->input('type');
        $division = $request->input('division');
        $dateRange = $request->input('date_range');

        // Build the query for driver_request_table
        $query = DB::table('driver_request_table')->orderBy('created_at', 'desc');

        // Apply filters
        if ($driverName) {
            $query->where('driver_name', 'like', "%$driverName%");
        }

        if ($type) {
            $query->where('type', 'like', "%$type%");
        }

        if ($division) {
            $query->where('division', 'like', "%$division%");
        }

        // Apply date range filter (if needed)
        if ($dateRange) {
            $now = now();
            switch ($dateRange) {
                case 'day':
                    $query->whereDate('date', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('date', [$now->startOfWeek()->toDateString(), $now->endOfWeek()->toDateString()]);
                    break;
                case 'month':
                    $query->whereMonth('date', $now->month)->whereYear('date', $now->year);
                    break;
                case '6months':
                    $query->whereBetween('date', [$now->subMonths(6)->toDateString(), $now->toDateString()]);
                    break;
                case 'year':
                    $query->whereYear('date', $now->year);
                    break;
            }
        }

        // Paginate the results
        $requests = $query->paginate(10);

        // Check if request is AJAX
        if ($request->ajax()) {
            return $this->getAjaxData($requests);
        }

        // Pass all necessary data to the view
        return view('AdminFuelRequest/history', [
            'requests' => $requests,
            'drivers' => $drivers,
            'productTypes' => $productTypes,
            'divisions' => $divisions,
            'activePage' => 'history',
        ]);
    }

    /**
     * Handle AJAX data requests for the history page
     */
    public function getHistoryData(Request $request)
    {
        // Reuse the same query building logic
        $driverName = $request->input('driver_name');
        $type = $request->input('type');
        $division = $request->input('division');
        $dateRange = $request->input('date_range');

        // Build the query for driver_request_table
        $query = DB::table('driver_request_table')->orderBy('created_at', 'desc');

        // Apply filters
        if ($driverName) {
            $query->where('driver_name', 'like', "%$driverName%");
        }

        if ($type) {
            $query->where('type', 'like', "%$type%");
        }

        if ($division) {
            $query->where('division', 'like', "%$division%");
        }

        // Apply date range filter
        if ($dateRange) {
            $now = now();
            switch ($dateRange) {
                case 'day':
                    $query->whereDate('date', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('date', [$now->startOfWeek()->toDateString(), $now->endOfWeek()->toDateString()]);
                    break;
                case 'month':
                    $query->whereMonth('date', $now->month)->whereYear('date', $now->year);
                    break;
                case '6months':
                    $query->whereBetween('date', [$now->subMonths(6)->toDateString(), $now->toDateString()]);
                    break;
                case 'year':
                    $query->whereYear('date', $now->year);
                    break;
            }
        }

        // Paginate the results
        $requests = $query->paginate(10);

        return $this->getAjaxData($requests);
    }

    /**
     * Format data for AJAX response
     */
    private function getAjaxData($requests)
    {
        // Render just the pagination links
        $paginationHtml = $requests->onEachSide(1)->links('pagination::bootstrap-4')->toHtml();
        
        return response()->json([
            'requests' => $requests,
            'pagination' => $paginationHtml,
        ]);
    }
}