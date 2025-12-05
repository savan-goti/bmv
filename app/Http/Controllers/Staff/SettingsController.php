<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Session;
use Exception;

class SettingsController extends Controller
{
    use ResponseTrait;

    /**
     * Show the staff settings page.
     */
    public function index()
    {
        $staff = Auth::guard('staff')->user();
        
        return view('staff.settings.index', compact('staff'));
    }

    /**
     * Update the staff settings.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $staff = Auth::guard('staff')->user();

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

            $staff->update($saveData);

            DB::commit();
            return $this->sendResponse('Settings updated successfully', $staff);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Get all active sessions for the staff.
     */
    public function getSessions()
    {
        try {
            $staff = Auth::guard('staff')->user();
            
            $sessions = Session::forUser($staff->id, 'staff')
                ->orderBy('last_activity', 'desc')
                ->get()
                ->map(function ($session) {
                    return [
                        'id' => $session->id,
                        'ip_address' => $session->ip_address,
                        'user_agent' => $session->user_agent,
                        'last_activity' => $session->last_activity,
                        'is_current' => $session->isCurrentSession(),
                    ];
                });

            return $this->sendResponse('Sessions retrieved successfully', $sessions);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Logout from a specific session.
     */
    public function logoutSession(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'session_id' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->sendValidationError($validator->errors());
            }

            $staff = Auth::guard('staff')->user();
            $sessionId = $request->session_id;

            // Check if trying to logout current session
            if ($sessionId === session()->getId()) {
                return $this->sendError('Cannot logout from current session. Use logout button instead.');
            }

            // Verify the session belongs to this staff
            $session = Session::forUser($staff->id, 'staff')
                ->where('id', $sessionId)
                ->first();

            if (!$session) {
                return $this->sendError('Session not found or does not belong to you.');
            }

            // Delete the session
            Session::logoutSession($staff->id, 'staff', $sessionId);

            return $this->sendSuccess('Session logged out successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Logout from all other sessions except current.
     */
    public function logoutOtherSessions()
    {
        try {
            $staff = Auth::guard('staff')->user();
            $currentSessionId = session()->getId();

            // Delete all sessions except current
            Session::logoutOtherSessions($staff->id, 'staff', $currentSessionId);

            return $this->sendSuccess('All other sessions logged out successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
