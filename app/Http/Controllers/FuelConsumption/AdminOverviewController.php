<?php

namespace App\Http\Controllers\FuelConsumption;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CarouselImage; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminOverviewController extends Controller
{
/**
 * Display the admin overview page.
 *
 * @return \Illuminate\Http\Response
 */
public function adminOverview()
{
    $activePage = 'overview';
    
    // Get carousel images
    $carouselImages = DB::table('carousel_images')
        ->orderBy('created_at', 'desc')
        ->get();
        
    // Get 5 most recent transactions
    $recentTransactions = DB::table('driver_request_table')
        ->where('status', 'approved')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
        
    // Get product types from driver_request_table
    $productTypes = DB::table('driver_request_table')
        ->select('type')
        ->where('status', 'approved')
        ->distinct()
        ->pluck('type')
        ->toArray();
            
    return view('FuelConsumption.adminOverview', compact(
        'activePage', 
        'carouselImages', 
        'recentTransactions',
        'productTypes'
    ));
}
    
    /**
     * Upload a new carousel image
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadCarouselImage(Request $request)
    {
        try {
            $request->validate([
                'carousel_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
        
            if ($request->hasFile('carousel_image')) {
                $image = $request->file('carousel_image');
                $imageName = time() . '.' . $image->extension();
                $path = $image->storeAs('carousel', $imageName, 'public');
                
                // Create a new carousel image record
                $carouselImage = CarouselImage::create([
                    'image_path' => $imageName
                ]);
        
                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded successfully!',
                    'image_url' => asset('storage/carousel/' . $imageName),
                    'image_id' => $carouselImage->id
                ]);
            }
        
            return response()->json([
                'success' => false,
                'message' => 'Image upload failed. No file was provided.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading carousel image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update an existing carousel image
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'image_id' => 'required|exists:carousel_images,id',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
        
            if ($request->hasFile('image')) {
                // Find the image
                $carouselImage = CarouselImage::findOrFail($request->image_id);
                
                // Delete the old image file
                if (Storage::disk('public')->exists('carousel/' . $carouselImage->image_path)) {
                    Storage::disk('public')->delete('carousel/' . $carouselImage->image_path);
                }
                
                // Upload new image
                $image = $request->file('image');
                $imageName = time() . '.' . $image->extension();
                $path = $image->storeAs('carousel', $imageName, 'public');
                
                // Update the image record
                $carouselImage->update([
                    'image_path' => $imageName
                ]);
        
                return response()->json([
                    'success' => true,
                    'message' => 'Image updated successfully!',
                    'image_url' => asset('storage/carousel/' . $imageName)
                ]);
            }
        
            return response()->json([
                'success' => false,
                'message' => 'Image update failed. No file was provided.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating carousel image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating image: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a carousel image
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'image_id' => 'required|exists:carousel_images,id'
            ]);
            
            // Find the image
            $carouselImage = CarouselImage::findOrFail($request->image_id);
            $imagePath = $carouselImage->image_path;
            
            // Delete the image file
            if (Storage::disk('public')->exists('carousel/' . $imagePath)) {
                Storage::disk('public')->delete('carousel/' . $imagePath);
            }
            
            // Delete the record
            $result = $carouselImage->delete();
            
            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete image record.'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting carousel image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting image: ' . $e->getMessage()
            ], 500);
        }
    }    
    
   /**
 * Get fuel usage data for the chart.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
public function getFuelUsageData(Request $request)
{
    $range = $request->input('range', 'month');
    $productType = $request->input('product_type', 'all');
    
    // Get the start and end dates based on the selected range
    $endDate = Carbon::now();
    $startDate = null;
    
    switch ($range) {
        case 'week':
            $startDate = Carbon::now()->startOfWeek();
            break;
            
        case 'year':
            $startDate = Carbon::now()->startOfYear();
            break;
            
        case 'month':
        default:
            $startDate = Carbon::now()->startOfMonth();
            break;
    }
    
    // Base query for date range
    $baseQuery = DB::table('driver_request_table')
        ->where('status', 'approved')
        ->whereBetween('created_at', [$startDate, $endDate]);
    
    // Prepare the data based on the range
    $labels = [];
    $data = [];
    
    // Generate date labels based on the range
    if ($range === 'week') {
        for ($i = 0; $i < 7; $i++) {
            $day = $startDate->copy()->addDays($i);
            $labels[] = $day->format('D');
        }
    } elseif ($range === 'year') {
        for ($i = 0; $i < 12; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $labels[] = $month->format('M');
        }
    } else { // month
        $daysInMonth = $endDate->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $day = $startDate->copy()->addDays($i - 1);
            $labels[] = $day->format('j');
        }
    }
    
    // For 'all' product types, get data for each product type separately
    if ($productType === 'all') {
        $productData = [];
        
        // Get all unique product types
        $productTypes = DB::table('driver_request_table')
            ->select('type')
            ->where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct()
            ->pluck('type')
            ->toArray();
        
        foreach ($productTypes as $type) {
            $productData[$type] = $this->getDataForProductType($type, $range, $startDate, $baseQuery->clone());
        }
        
        return response()->json([
            'labels' => $labels,
            'productData' => $productData
        ]);
    } else {
        // For specific product type, get only that data
        $data = $this->getDataForProductType($productType, $range, $startDate, $baseQuery->clone());
        
        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}

/**
 * Helper method to get data for a specific product type
 */
private function getDataForProductType($productType, $range, $startDate, $query)
{
    $data = [];
    $query->where('type', $productType);
    
    if ($range === 'week') {
        // For week view
        for ($i = 0; $i < 7; $i++) {
            $day = $startDate->copy()->addDays($i);
            $dayData = clone $query;
            $value = $dayData->whereDate('created_at', $day->toDateString())
                ->sum('quantity');
            $data[] = $value;
        }
    } elseif ($range === 'year') {
        // For year view
        for ($i = 0; $i < 12; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $monthData = clone $query;
            $value = $monthData
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('quantity');
            $data[] = $value;
        }
    } else {
        // For month view
        $daysInMonth = $startDate->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $day = $startDate->copy()->addDays($i - 1);
            $dayData = clone $query;
            $value = $dayData->whereDate('created_at', $day->toDateString())
                ->sum('quantity');
            $data[] = $value;
        }
    }
    
    return $data;
}

}