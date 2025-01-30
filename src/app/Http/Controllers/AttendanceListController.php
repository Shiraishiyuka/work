<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceListController extends Controller
{
    public function attendance_list($year = null, $month = null)
    {
        $currentDate = Carbon::now();

    $year = $year ?? $currentDate->year;
    $month = $month ?? $currentDate->month;

    $attendances = Attendance::where('user_id', Auth::id())
                             ->whereYear('date', $year)
                             ->whereMonth('date', $month)
                             ->orderBy('date', 'desc')
                             ->limit(100) // 最大100件まで取得
                             ->get();

    return view('attendance_list', compact('attendances', 'year', 'month'));
    }
}
