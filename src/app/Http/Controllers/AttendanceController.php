<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth; // Auth ファサードを正しくインポート
use App\Models\User;

class AttendanceController extends BaseController
{
   public function show(Request $request)
{
    // リダイレクト処理
    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }

    // Carbon のロケールを日本語に設定
    Carbon::setLocale('ja');

    // 最新の勤怠データを取得
    $attendance = \App\Models\Attendance::where('user_id', auth()->id())
                                        ->whereDate('date', Carbon::now()->format('Y-m-d'))
                                        ->first();

    // ✅ 勤怠データがない場合、セッションを `not_working` にする（初回ログイン対応）
    if (!$attendance) {
        session(['attendance_status' => 'not_working']);
    }

    // 現在の時刻を取得
    $currentDateTime = Carbon::now();

    // セッションから現在の勤怠状態を取得
    $status = session('attendance_status');

    // ビューに変数を渡す
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
    $attendance->user_id = auth()->id(); // ログインユーザーのIDを取得
    $attendance->date = Carbon::now()->format('Y-m-d');
    $attendance->start_time = Carbon::now()->format('H:i:s');
    $attendance->save();

    // 出勤完了後に勤怠画面へリダイレクト
    return redirect()->route('attendance.show');
}

    public function takeBreak(Request $request)
{
    session(['attendance_status' => 'on_break']);

    $attendance = \App\Models\Attendance::where('user_id', auth()->id())
                                        ->whereDate('date', Carbon::now()->format('Y-m-d'))
                                        ->first();

    if (!$attendance->break_start_time) { // 休憩中でない場合のみセット
    $attendance->break_start_time = Carbon::now()->format('H:i:s');
    $attendance->save();
}

    return redirect()->route('attendance.show');
}


    public function endBreak(Request $request)
{
    session(['attendance_status' => 'working']);

    $attendance = \App\Models\Attendance::where('user_id', auth()->id())
                                        ->whereDate('date', Carbon::now()->format('Y-m-d'))
                                        ->first();

    if ($attendance && $attendance->break_start_time) {
        $breakStartTime = Carbon::createFromFormat('H:i:s', $attendance->break_start_time);
        $breakEndTime = Carbon::now();

        // 休憩時間を計算
        $breakMinutes = $breakStartTime->diffInMinutes($breakEndTime);

        // データベースに保存
        $attendance->break_end_time = $breakEndTime->format('H:i:s');
        $attendance->break_minutes += $breakMinutes; // 休憩の合計時間を更新
        $attendance->save();
    }

    return redirect()->route('attendance.show');
}


    public function endWork(Request $request)
{
    // 退勤ボタンを押したときの処理
    session(['attendance_status' => 'finished']);

    // 出勤中の勤怠データを取得
    $attendance = \App\Models\Attendance::where('user_id', auth()->id())
                                        ->whereDate('date', Carbon::now()->format('Y-m-d'))
                                        ->first();

    // 退勤時刻を保存
    $endTime = Carbon::now();
    $attendance->end_time = $endTime->format('H:i:s');

    // 勤務時間を計算（分単位）
    $startTime = Carbon::createFromFormat('H:i:s', $attendance->start_time);
    $workMinutes = $startTime->diffInMinutes($endTime) - $attendance->break_minutes;

    // 勤務の合計時間を保存
    $attendance->work_minutes = $workMinutes;
    $attendance->save();

    return redirect()->route('attendance.show');
}
}