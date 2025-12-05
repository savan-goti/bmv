<?php

namespace App\Http\Controllers\Admin;

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
     * Show the admin settings page.
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        return view('admin.settings.index', compact('admin'));
    }

    /**
     * Update the admin settings.
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $admin = Auth::guard('admin')->user();

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

            $admin->update($saveData);

            DB::commit();
            return $this->sendResponse('Settings updated successfully', $admin);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Get all active sessions for the admin.
     */
    public function getSessions()
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            $sessions = Session::forUser($admin->id, 'admin')
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

            $admin = Auth::guard('admin')->user();
            $sessionId = $request->session_id;

            // Check if trying to logout current session
            if ($sessionId === session()->getId()) {
                return $this->sendError('Cannot logout from current session. Use logout button instead.');
            }

            // Verify the session belongs to this admin
            $session = Session::forUser($admin->id, 'admin')
                ->where('id', $sessionId)
                ->first();

            if (!$session) {
                return $this->sendError('Session not found or does not belong to you.');
            }

            // Delete the session
            Session::logoutSession($admin->id, 'admin', $sessionId);

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
            $admin = Auth::guard('admin')->user();
            $currentSessionId = session()->getId();

            // Delete all sessions except current
            Session::logoutOtherSessions($admin->id, 'admin', $currentSessionId);

            return $this->sendSuccess('All other sessions logged out successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
