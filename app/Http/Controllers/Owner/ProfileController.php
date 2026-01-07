<?php

namespace App\Http\Controllers\Owner;

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
     * Show the admin profile page.
     */
    public function index()
    {
        $owner = Auth::guard('owner')->user();
        
        return view('owner.profile.index', compact('owner')); // Render the new profile view
    }

    /**
     * Update the admin profile information.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $owner = Auth::guard('owner')->user();

            $validator = Validator::make($request->all(), [
                'full_name'  => 'required|string|max:255',
                'phone' => 'nullable|string|max:15|unique:owners,phone,' . $owner->id,
                'dob' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'address' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $saveData = [
                'full_name' => $request->full_name,
                'phone' => $request->phone??null,
                'dob' => $request->dob??null,
                'gender' => $request->gender??null,
                'address' => $request->address??null,
            ];

            if ($request->hasFile('profile_image')) {
                if ($owner->profile_image) {
                    deleteImage($owner->profile_image, OWNER_PROFILE_IMAGE_PATH);
                }
                $saveData['profile_image'] = uploadImgFile($request->profile_image, OWNER_PROFILE_IMAGE_PATH);
            }

            $owner->update($saveData);

            DB::commit();
            return $this->sendResponse('Profile updated successfully',$owner);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Update the admin password.
     */
    public function updatePassword(Request $request)
    {
        try {
            DB::beginTransaction();

            $owner = Auth::guard('owner')->user();

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
            if (!Hash::check($request->oldPassword, $owner->password)) {
                if ($request->expectsJson()) {
                    return $this->sendError('Current password is incorrect');
                }
            }

            $owner->update([
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
     * Get admin login history.
     */
    public function loginHistory(Request $request)
    {
        $owner = Auth::guard('owner')->user();
        $query = LoginHistory::where('owner_id', $owner->id)->orderBy('login_at', 'desc');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('login_at', function ($row) {
                return $row->login_at->format('d M Y, h:i A');
            })
            ->make(true);
    }
}
