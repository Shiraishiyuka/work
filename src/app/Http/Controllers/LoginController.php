<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // 認証を試みる
        if (!Auth::attempt($credentials)) {
            // 認証失敗時にカスタムエラーメッセージを返す
            return back()->withErrors([
                'login_error' => 'ログイン情報が登録されていません',
            ])->withInput($request->only('email'));
        }

        // ログイン成功後にセッションに初期状態を設定
        session(['attendance_status' => 'not_working']);

        return redirect()->route('attendance.show');

    }
}
