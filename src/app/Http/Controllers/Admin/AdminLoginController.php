<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function admin_login()
    {
        return view('admin.auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 管理者のみログインできるようにする
        if (Auth::attempt(array_merge($credentials, ['is_admin' => true]))) {
            $request->session()->regenerate();
            return redirect()->route('admin.attendance.list');
        }

        return back()->withErrors([
            'email' => '認証に失敗しました。',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
