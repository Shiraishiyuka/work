<?php

namespace App\Http\Controllers;

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


        $credentials = $request->only('email', 'password');


        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'ログイン情報が登録されていません',
            ])->withInput($request->only('email'));
        }

        $user = Auth::user();

        $token = Str::random(40);
        Session::put('two_factor_token', $token);
        Session::put('two_factor_authenticated', false);


        Mail::to($user->email)->send(new TwoFactorAuthMail($user, $token));


        return redirect()->route('two-factor.form');


    }
}
