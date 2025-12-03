<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Exception;

class SettingsController extends Controller
{
    use ResponseTrait;

    /**
     * Show the seller settings page.
     */
    public function index()
    {
        $seller = Auth::guard('seller')->user();
        
        return view('seller.settings.index', compact('seller'));
    }

    /**
     * Update the seller settings.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $seller = Auth::guard('seller')->user();

            $validator = Validator::make($request->all(), [
                'two_factor_enabled' => 'nullable|boolean',
                'email_verified' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $saveData = [];

            // Handle two-factor authentication toggle
            if ($request->has('two_factor_enabled')) {
                $saveData['two_factor_enabled'] = $request->two_factor_enabled ? true : false;
            }

            // Handle email verification
            if ($request->has('email_verified')) {
                if ($request->email_verified) {
                    $saveData['email_verified_at'] = now();
                } else {
                    $saveData['email_verified_at'] = null;
                }
            }

            $seller->update($saveData);

            DB::commit();
            return $this->sendResponse('Settings updated successfully', $seller);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
