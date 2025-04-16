<?php

namespace App\Http\Controllers\AdminFuelRequest;

use App\Http\Controllers\Controller;


class DocumentController extends Controller
{
    public function showDocument()
    {
        return view('AdminFuelRequest/document', ['activePage' => 'document']);
    }
}
