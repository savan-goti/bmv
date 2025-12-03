<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the seller dashboard.
     */
    public function index()
    {
        $seller_data = Auth::guard('seller')->user();
        
        return view('seller.dashboard.index', compact(
            'seller_data'
        ));
    }
}
