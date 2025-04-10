<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\BaseController;

class AttendanceListController extends BaseController
{
    public function attendance_list(Request $request, $year = null, $month = null)
    {
        Carbon::setLocale('ja');


        $year = $request->query('year', $year ?? Carbon::today()->year);
        $month = $request->query('month', $month ?? Carbon::today()->month);


        $currentDate = Carbon::createFromDate($year, $month, 1);

        // **前月・次月を計算**
        $previousDate = $currentDate->copy()->subMonth();
        $nextDate = $currentDate->copy()->addMonth();

        // **指定した月の勤怠データを取得**
        $attendances = Attendance::where('user_id', Auth::id())
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'asc')
            ->get();

        return view('attendance_list', [
            'currentDate' => $currentDate,
            'previousDate' => $previousDate, 
            'nextDate' => $nextDate,
            'attendances' => $attendances,
            'year' => $year,
            'month' => $month
        ]);
    }
}
