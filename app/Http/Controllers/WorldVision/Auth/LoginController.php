<?php

namespace App\Http\Controllers\WorldVision\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginSubmit(LoginRequest $request)
    {
        //Validate Income Request
        $credentials = $request->validated();

        // Attempt to Authenticate the User
        if (Auth::attempt(['email' => $credentials['email'],'password' => $credentials['password']])) {
            return redirect()->route('admin.home')->with('success', 'Login Successfully!!!');
        }

    }

    public function logout()
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();

        return redirect()->route('login')->with('success', 'Logout Successfully!!!');
    }
}
