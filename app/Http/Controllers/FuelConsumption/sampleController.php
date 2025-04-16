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
    public function adminInventory()
    {
        $activePage = 'inventory';
        
        return view('FuelConsumption.adminInventory', compact('activePage'));
    }
}