<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $admin_data = Auth::guard('admin')->user();
        
        // Get counts for dashboard cards - filter staff by admin_id
        $staffCount = Staff::where('admin_id', $admin_data->id)->count();
        
        // Get sellers created by this admin using seller_management table
        $sellerCount = Seller::whereHas('managementRecords', function($query) use ($admin_data) {
            $query->where('created_by_type', get_class($admin_data))
                  ->where('created_by_id', $admin_data->id)
                  ->where('action', 'created');
        })->count();
        
        return view('admin.dashboard.index', compact(
            'admin_data',
            'staffCount',
            'sellerCount'
        ));
    }
}
