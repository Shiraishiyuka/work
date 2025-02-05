<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminListController extends Controller
{
    public function attendance_list(Request $request, $year = null, $month = null)
    {
        if (!$year || !$month) {
            $currentDate = Carbon::now();
            $year = $currentDate->year;
            $month = $currentDate->month;
        }

        // 現在の時刻を取得
        $currentDateTime = Carbon::now();

        // **ここで `$attendances` を取得**
        $attendances = Attendance::whereYear('date', $year)
                                 ->whereMonth('date', $month)
                                 ->orderBy('date', 'desc')
                                 ->get();

        return view('admin.attendance_list', [
            'year' => (int)$year,
            'month' => (int)$month,
            'currentDateTime' => $currentDateTime,
            'attendances' => $attendances, // ← `$attendances` を渡す
        ]);
    }
}
