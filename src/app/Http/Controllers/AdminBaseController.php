<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Auth ファサードを正しくインポート
use App\Models\User;


class AdminBaseController extends Controller
{

    protected function handleRedirects(Request $request)
    {
     //管理者用
        //勤怠一覧
        if ($request->has('admin_attendance')) {
            return redirect()->route('admin.attendance.list');
        }


        //スタッフ別
        if ($request->has('staff')) {
            return redirect()->route('admin.staff_list');
        }

        //申請一覧
        if ($request->has('admin_request')) {
            return redirect()->route('admin.application_request');
        }

        if ($request->has('admin_logout')) {
            Auth::logout();
            return redirect()->route('admin.attendance.list');
        }

        if ($request->has('admin_login')) {
            return redirect()->route('admin.login');
        }


    }

}
