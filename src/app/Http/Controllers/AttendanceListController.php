<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceListController extends Controller
{
    public function attendance_list() {
        return view('attendance_list');
    }
}
