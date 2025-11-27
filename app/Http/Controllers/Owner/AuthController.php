<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ResponseTrait;
    public function login()
    {
        $setting = \App\Models\Setting::first();
        return view('owner.auth.login', compact('setting'));
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

        if (Auth::guard('owner')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->sendSuccess('login successful', 201);
        }

        return $this->sendError('Invalid email or password');
    }

    public function logout()
    {
        Auth::guard('owner')->logout();

        return redirect()->route('owner.login');
    }
}
