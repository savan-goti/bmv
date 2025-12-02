<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\Staff;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the owner dashboard.
     */
    public function index()
    {
        $owner_data = Auth::guard('owner')->user();
        
        // Get counts for dashboard cards
        $adminCount = Admin::count();
        $sellerCount = Seller::count();
        $staffCount = Staff::count();
        $customerCount = Customer::count();
        
        return view('owner.dashboard.index', compact(
            'owner_data',
            'adminCount',
            'sellerCount',
            'staffCount',
            'customerCount'
        ));
    }
}
