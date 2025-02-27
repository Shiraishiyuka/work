<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Adjust;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceRequest;
use App\Http\Controllers\BaseController;

class AttendanceDetailController extends BaseController
{
     public function attendancedetail($id)
{
    // 勤怠データを取得
    $attendance = Attendance::with('user')->findOrFail($id);

     // **該当勤怠データの修正申請が「承認待ち」かどうかをチェック**
    $hasPendingApproval = Adjust::where('attendance_id', $id)
        ->where('status', 'pending') // **ステータスが承認待ちのもの**
        ->exists(); // **存在するかどうか**

    // ビューにデータを渡す
    return view('attendance_detail', compact('attendance', 'hasPendingApproval'));
}


   public function update(AttendanceRequest $request, $id)
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

    return redirect()->route('correctionrequest')->with('message', '修正申請が完了しました。');
    }
}
