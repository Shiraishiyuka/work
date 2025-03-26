<?php

namespace App\Http\Controllers;

/*use Illuminate\Http\Request;
use App\Models\User;*/
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\TwoFactorAuthMail;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request) {

        /*$request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);*/

        $credentials = $request->only('email', 'password');

        // 認証を試みる
        if (!Auth::attempt($credentials)) {
            // 認証失敗時にカスタムエラーメッセージを返す
            return back()->withErrors([
                'email' => 'ログイン情報が登録されていません',
            ])->withInput($request->only('email'));
        }
        // ユーザー情報を取得
        $user = Auth::user();

        // 認証トークンを生成し、セッションに保存
        $token = Str::random(40);
        Session::put('two_factor_token', $token);
        Session::put('two_factor_authenticated', false);

        // 認証メールを送信
        Mail::to($user->email)->send(new TwoFactorAuthMail($user, $token));

        // 認証ページへリダイレクト
        return redirect()->route('two-factor.form');

        /*ログイン成功後にセッションに初期状態を設定
        session(['attendance_status' => 'not_working']);*/


    }
}
