<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Adjust;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceRequest;
use App\Http\Controllers\AdminBaseController;

class AdminAttendanceController extends AdminBaseController
{
    public function admin_attendance(Request $request, $id)
{
    // リダイレクト処理を呼び出し
    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }
    
    $attendance = Attendance::with('user')->findOrFail($id);

    // **該当勤怠データの修正申請が「承認待ち」かどうかをチェック**
    $hasPendingApproval = Adjust::where('attendance_id', $id)
        ->where('status', 'pending') // **ステータスが承認待ちのもの**
        ->exists(); // **存在するかどうか**

    // **休憩時間 (分) を計算**
    $break_minutes = 0;
    if ($attendance->break_start_time && $attendance->break_end_time) {
        $start = \Carbon\Carbon::parse($attendance->break_start_time);
        $end = \Carbon\Carbon::parse($attendance->break_end_time);
        $break_minutes = $start->diffInMinutes($end);
    }

    return view('admin.admin_attendance', compact('attendance', 'hasPendingApproval', 'break_minutes'));
}

    
     public function update_attendance(AttendanceRequest $request, $id)
    {
        // 勤怠データを取得（元の日付を取得）
        $attendance = Attendance::findOrFail($id);

        // `date` カラムに適切な値をセット（修正前の勤怠データの日付を利用）
        $fullDate = sprintf('%04d-%s', $request->year, $request->month_day);

        // 修正データを `adjusts` テーブルに保存（`attendances` テーブルは更新しない）
        Adjust::create([
        'attendance_id' => $id,
        'user_id' => auth()->id(),
        'original_date' => $attendance->date, // 修正前の日付
        'date' => $fullDate, // 修正後の日付
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'break_start_time' => $request->break_start_time,
        'break_end_time' => $request->break_end_time,
        'remarks' => $request->remarks,
    ]);


        return redirect()->route('admin.application_request')->with('message', '修正申請が完了しました。');
    }
}
