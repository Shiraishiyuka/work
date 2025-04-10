<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\TwoFactorAuthMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TwoFactorAuthController extends Controller
{
    public function showForm()
    {

        return view('auth.two_factor_auth');
    }

    public function mail()
    {
        return view('mail.two_factor_auth');
    }

    public function verify(Request $request)
    {
        $token = $request->query('token');

        // トークンが一致すれば認証成功
        if ($token === Session::get('two_factor_token')) {
            Session::put('two_factor_authenticated', true);
            return redirect()->route('attendance.show');
        }

        return redirect()->route('two-factor.form')->withErrors(['token' => '無効な認証トークンです。']);
    }

    public function resend()
    {
        $user = Auth::user();
        $token = Str::random(40);

        Session::put('two_factor_token', $token);
        Mail::to($user->email)->send(new TwoFactorAuthMail($user, $token));

        return back()->with('message', '認証メールを再送しました。');
    }
}
