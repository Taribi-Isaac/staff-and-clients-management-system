<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index()
    {
        // This will show the main finance page with book descriptions and navigation
        return view('finance.index');
    }
}
