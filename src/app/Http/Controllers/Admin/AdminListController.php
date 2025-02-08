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
        // URLパラメータから `date` を取得（指定がない場合は今日）
    $date = $request->query('date', Carbon::today()->toDateString());

    // Carbon インスタンスに変換
    $currentDate = Carbon::parse($date);

    // 前日・次日を計算
    $previousDate = $currentDate->copy()->subDay()->toDateString();
    $nextDate = $currentDate->copy()->addDay()->toDateString();

    // 該当日の勤怠データを取得
    $attendances = Attendance::with('user')
        ->whereDate('date', $currentDate)
        ->orderBy('start_time', 'asc')
        ->get();
                                 

        return view('admin.attendance_list', compact('currentDate', 'previousDate', 'nextDate', 'attendances'));
    }
}
