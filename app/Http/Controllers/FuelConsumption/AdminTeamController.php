<?php

namespace App\Http\Controllers\FuelConsumption;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTeamController extends Controller
{
    /**
     * Display the inventory page.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminTeam()
    {
        $activePage = 'team';
        
        return view('FuelConsumption.adminTeam', compact('activePage'));
    }
}