<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;

class AuthController extends Controller
{
    use ResponseTrait;
    
    public function login()
    {
        $setting = Setting::first();
        return view('staff.auth.login', compact('setting'));
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        if (Auth::guard('staff')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Update last login information
            $staff = Auth::guard('staff')->user();
            $staff->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            return $this->sendSuccess('login successful', 201);
        }

        return $this->sendError('Invalid email or password');
    }

    public function logout()
    {
        Auth::guard('staff')->logout();

        return redirect()->route('staff.login');
    }
}
