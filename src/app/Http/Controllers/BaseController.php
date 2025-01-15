<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Auth ファサードを正しくインポート
use App\Models\User;

class BaseController extends Controller
{
    protected function handleRedirects(Request $request)
    {
        if ($request->has('logout')) {
            Auth::logout();
            return redirect()->route('attendance.show');
        }

        if ($request->has('login') ) {
            return redirect()->route('login.show');
        }

        if ($request->has('mypage')) {
            return redirect('mypage');
        }

        if ($request->has('sell')) {
            return redirect()->route('sell');
        }

        return null; // リダイレクト不要の場合
    }
}
