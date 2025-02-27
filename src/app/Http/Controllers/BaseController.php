<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Auth ファサードを正しくインポート
use App\Models\User;

class BaseController extends Controller
{
    protected function handleRedirects(Request $request)
    {
        //勤怠
        if ($request->has('attendance')) {
            return redirect()->route('attendance.show');
        }


        //勤怠一覧
        if ($request->has('attendance-list')) {
            return redirect()->route('attendance_list');
        }

        //申請
        if ($request->has('request')) {
            return redirect()->route('correctionrequest');
        }

        if ($request->has('logout')) {
            Auth::logout();
            return redirect()->route('attendance.show');
        }

        if ($request->has('login')) {
            return redirect()->route('login.show');
        }




        return abort(404); // **🔹 意図しないアクセスの場合、404エラーを返す**
    }
}
