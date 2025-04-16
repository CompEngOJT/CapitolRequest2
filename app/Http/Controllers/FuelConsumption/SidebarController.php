<?php

namespace App\Http\Controllers\FuelConsumption;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    public function loadPage($page)
    {
        // Check if the request is AJAX
        if(request()->ajax()) {
            // Return only the content part of the view
            return view("pages.$page")->render();
        }
        
        // For non-AJAX requests, return the full layout with content
        return view("pages.$page", ['activePage' => $page]);
    }
}