<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (Session::has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $admin_username = config('app.admin_username');
        $admin_password = config('app.admin_password');

        if ($username === $admin_username && $password === $admin_password) {
            Session::put('admin_logged_in', true);
            Session::put('admin_username', $username);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['Invalid credentials']);
    }

    public function logout()
    {
        Session::forget('admin_logged_in');
        Session::forget('admin_username');

        return redirect()->route('admin.login');
    }
}
