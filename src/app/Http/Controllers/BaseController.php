<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    protected function handleRedirects(Request $request)
    {

        if ($request->has('attendance')) {
            return redirect()->route('attendance.show');
        }



        if ($request->has('attendance-list')) {
            return redirect()->route('attendance_list');
        }


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

    }
}
