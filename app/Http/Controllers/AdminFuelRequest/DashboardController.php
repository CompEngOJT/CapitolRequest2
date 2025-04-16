<?php

namespace App\Http\Controllers\AdminFuelRequest;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Image;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        return view('AdminFuelRequest/dashboard', ['activePage' => 'dashboard']);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('uploads'), $imageName);
    
            $imagePath = 'uploads/' . $imageName;
            
            // Find the latest image or create a new one if none exists
            $latestImage = Image::latest()->first();
            
            if ($latestImage) {
                // Delete the old file if it exists
                if (file_exists(public_path($latestImage->image_path))) {
                    unlink(public_path($latestImage->image_path));
                }
                
                // Update the existing record
                $latestImage->update(['image_path' => $imagePath]);
            } else {
                // Create a new record if none exists
                Image::create(['image_path' => $imagePath]);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Image updated successfully!',
                'image_url' => asset($imagePath)
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Image upload failed.'
        ]);
    }
}