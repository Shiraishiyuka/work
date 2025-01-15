<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceDetailController extends Controller
{
    public function attendancedetail() {
        return view('attendance_detail');
    }
}
