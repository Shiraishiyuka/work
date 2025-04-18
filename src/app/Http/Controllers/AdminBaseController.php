<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AdminBaseController extends Controller
{

    protected function handleRedirects(Request $request)
    {

        if ($request->has('admin_attendance')) {
            return redirect()->route('admin.attendance.list');
        }



        if ($request->has('staff')) {
            return redirect()->route('admin.staff_list');
        }

        if ($request->has('admin_request')) {
            return redirect()->route('admin.correction_request');
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
