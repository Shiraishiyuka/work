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
        // 指定がない場合は今日の日付を設定
        if (!$date) {
            $date = Carbon::today()->toDateString();
        }

        // **Carbon インスタンスに変換**
        $currentDate = Carbon::parse($date);

        // **前日・次日を計算**
        $previousDate = $currentDate->copy()->subDay()->toDateString();
        $nextDate = $currentDate->copy()->addDay()->toDateString();

        // **この日付のデータのみ取得**
        $attendances = Attendance::with('user')
            ->whereDate('date', $currentDate)
            ->orderBy('start_time', 'asc')
            ->get();

        return view('admin.attendance_list', [
            'currentDate' => $currentDate,  // **Carbon インスタンス**
            'previousDate' => $previousDate, 
            'nextDate' => $nextDate,
            'attendances' => $attendances,
        ]);
    }
}
