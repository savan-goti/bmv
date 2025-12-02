<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the staff dashboard.
     */
    public function index()
    {
        $staff_data = Auth::guard('staff')->user();
        
        // Get sellers created by this staff using seller_management table
        $sellerCount = Seller::whereHas('managementRecords', function($query) use ($staff_data) {
            $query->where('created_by_type', get_class($staff_data))
                  ->where('created_by_id', $staff_data->id)
                  ->where('action', 'created');
        })->count();
        
        return view('staff.dashboard.index', compact(
            'staff_data',
            'sellerCount'
        ));
    }
}
