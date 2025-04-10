<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BreakTime;


class AttendanceController extends BaseController
{
   public function show(Request $request)
{

    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }

    Carbon::setLocale('ja');

    // 最新の勤怠データを取得
    $attendance = \App\Models\Attendance::where('user_id', auth()->id())
                                        ->whereDate('date', Carbon::now()->format('Y-m-d'))
                                        ->first();


    if (!$attendance) {
        session(['attendance_status' => 'not_working']);
    }


    $currentDateTime = Carbon::now();


    $status = session('attendance_status');


    return view('attendance', [
        'currentDateTime' => $currentDateTime,
        'status' => $status
    ]);
}

    public function startWork(Request $request)
{
    // 出勤ボタンを押したときの処理
    session(['attendance_status' => 'working']);

    // 出勤時刻を保存
    $attendance = new \App\Models\Attendance();
    $attendance->user_id = auth()->id();
    $attendance->date = Carbon::now()->format('Y-m-d');
    $attendance->start_time = Carbon::now()->format('H:i:s'); 
    $attendance->save();

    return redirect()->route('attendance.show');
}


    public function takeBreak(Request $request)
{
    session(['attendance_status' => 'on_break']);

    $attendance = \App\Models\Attendance::where('user_id', auth()->id())
                                        ->whereDate('date', Carbon::now()->format('Y-m-d'))
                                        ->first();


    BreakTime::create([
        'attendance_id' => $attendance->id,
        'start_time' => Carbon::now()->format('H:i:s'),
    ]);

    return redirect()->route('attendance.show');
}


    public function endBreak(Request $request)
{
    session(['attendance_status' => 'working']);

    $attendance = \App\Models\Attendance::where('user_id', auth()->id())
                                        ->whereDate('date', Carbon::now()->format('Y-m-d'))
                                        ->first();

    // 終了していない最新の休憩を取得
    $break = $attendance->breakTimes()
                        ->whereNull('end_time')
                        ->latest()
                        ->first();

    if ($break) {
        $start = Carbon::createFromFormat('H:i:s', $break->start_time);
        $end = Carbon::now();

        $break->end_time = $end->format('H:i:s');
        $break->save();

        // 合計休憩時間を再計算
        $totalBreak = $attendance->breakTimes()
    ->whereNotNull('end_time')
    ->get()
    ->reduce(function ($carry, $bt) {
        $s = Carbon::createFromFormat('H:i:s', $bt->start_time);
        $e = Carbon::createFromFormat('H:i:s', $bt->end_time);

        if ($e < $s) {
            $e->addDay();
        }

        return $carry + $s->diffInMinutes($e);
    }, 0);

        $attendance->break_minutes = $totalBreak;
        $attendance->save();
    }

    return redirect()->route('attendance.show');
}



public function endWork(Request $request)
{
    session(['attendance_status' => 'finished']);

    $attendance = \App\Models\Attendance::where('user_id', auth()->id())
                                        ->whereDate('date', Carbon::now()->format('Y-m-d'))
                                        ->first();

    $endTime = Carbon::now();
    $attendance->end_time = $endTime;

    $startTime = Carbon::createFromFormat('H:i:s', $attendance->start_time);


    $totalBreak = $attendance->breakTimes()
    ->whereNotNull('end_time')
    ->get()
    ->reduce(function ($carry, $bt) {
        $s = Carbon::createFromFormat('H:i:s', $bt->start_time);
        $e = Carbon::createFromFormat('H:i:s', $bt->end_time);

        if ($e < $s) {
            $e->addDay(); // ✅ 日またぎ対応
        }

        return $carry + $s->diffInMinutes($e);
    }, 0);

    $attendance->break_minutes = $totalBreak;

    // 勤務時間の再計算
    $workMinutes = $startTime->diffInMinutes($endTime) - $totalBreak;
    $attendance->work_minutes = max(0, $workMinutes);

    $attendance->save();

    return redirect()->route('attendance.show');
}

}