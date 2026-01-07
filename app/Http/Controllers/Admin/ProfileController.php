<?php

namespace App\Http\Controllers\Admin;

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
        $admin = Auth::guard('admin')->user();
        
        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update the admin profile information.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $admin = Auth::guard('admin')->user();

            $validator = Validator::make($request->all(), [
                'name'  => 'required|string|max:255',
                'phone' => 'nullable|string|max:15|unique:admins,phone,' . $admin->id,
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
                'address' => 'nullable|string',
                'father_name' => 'nullable|string|max:255',
                'education' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $saveData = [
                'name' => $request->name,
                'phone' => $request->phone ?? null,
                'date_of_birth' => $request->date_of_birth ?? null,
                'gender' => $request->gender ?? null,
                'address' => $request->address ?? null,
                'father_name' => $request->father_name ?? null,
                'education' => $request->education ?? null,
            ];

            if ($request->hasFile('profile_image')) {
                deleteImage($admin->profile_image, ADMIN_PROFILE_IMAGE_PATH);
                $saveData['profile_image'] = uploadImgFile($request->profile_image, ADMIN_PROFILE_IMAGE_PATH);
            }

            $admin->update($saveData);

            DB::commit();
            return $this->sendResponse('Profile updated successfully', $admin);
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

            $admin = Auth::guard('admin')->user();

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
            if (!Hash::check($request->oldPassword, $admin->password)) {
                if ($request->expectsJson()) {
                    return $this->sendError('Current password is incorrect');
                }
            }

            $admin->update([
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
        $admin = Auth::guard('admin')->user();
        $query = LoginHistory::where('admin_id', $admin->id)->orderBy('login_at', 'desc');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->editColumn('login_at', function ($row) {
                return $row->login_at->format('d M Y, h:i A');
            })
            ->make(true);
    }
}
