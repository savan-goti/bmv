<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait;
use App\Models\LoginHistory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Exception;

class ProfileController extends Controller
{
    use ResponseTrait;

    /**
     * Show the seller profile page.
     */
    public function index()
    {
        $seller = Auth::guard('seller')->user();
        return view('seller.profile.index', compact('seller'));
    }

    /**
     * Update the seller profile information.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $seller = Auth::guard('seller')->user();

            $validator = Validator::make($request->all(), [
                'business_name' => 'required|string|max:255',
                'owner_name' => 'required|string|max:255',
                'email' => 'required|email|max:150|unique:sellers,email,' . $seller->id,
                'phone' => 'nullable|string|max:15|unique:sellers,phone,' . $seller->id,
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'address' => 'nullable|string',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'pincode' => 'nullable|string|max:10',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $saveData = [
                'business_name' => $request->business_name,
                'owner_name' => $request->owner_name,
                'email' => $request->email,
                'phone' => $request->phone ?? null,
                'date_of_birth' => $request->date_of_birth ?? null,
                'gender' => $request->gender ?? null,
                'address' => $request->address ?? null,
                'city' => $request->city ?? null,
                'state' => $request->state ?? null,
                'country' => $request->country ?? null,
                'pincode' => $request->pincode ?? null,
            ];

            if ($request->hasFile('business_logo')) {
                deleteImage($seller->business_logo, SELLER_DOCUMENT_PATH);
                $saveData['business_logo'] = uploadImgFile($request->business_logo, SELLER_DOCUMENT_PATH);
            }

            $seller->update($saveData);

            DB::commit();
            return $this->sendResponse('Profile updated successfully', $seller);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Update the seller password.
     */
    public function updatePassword(Request $request)
    {
        try {
            DB::beginTransaction();

            $seller = Auth::guard('seller')->user();

            $validator = Validator::make($request->all(), [
                'oldPassword' => 'required',
                'newPassword' => 'required|string|min:8|confirmed:confirm_password',
                'confirm_password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return $this->sendValidationError($validator->errors());
                }
            }

            // Check if current password is correct
            if (!Hash::check($request->oldPassword, $seller->password)) {
                if ($request->expectsJson()) {
                    return $this->sendError('Current password is incorrect');
                }
            }

            $seller->update([
                'password' => Hash::make($request->newPassword),
            ]);

            DB::commit();
            return $this->sendSuccess('Password updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Get seller login history.
     */
    public function loginHistory(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $query = LoginHistory::where('seller_id', $seller->id)->orderBy('login_at', 'desc');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('login_at', function ($row) {
                return $row->login_at->format('d M Y, h:i A');
            })
            ->make(true);
    }
}
