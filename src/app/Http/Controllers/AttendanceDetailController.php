<?php

namespace App\Http\Controllers;

/*use Illuminate\Http\Request;*/
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

    // **該当勤怠データの修正申請（承認待ち or 承認済み）を取得**
    $adjust = Adjust::where('attendance_id', $id)
        ->where('status', 'pending') // 承認待ちのデータを優先
        ->orWhere('status', 'approved')
        ->latest() // 最新の修正データを取得
        ->first(); // 1件だけ取得

    /*$adjust = Adjust::where('attendance_id', $id)
    ->where(function ($query) {
        $query->where('status', 'pending')
              ->orWhere('status', 'approved');
    })
    ->latest()
    ->first();
    */

    // **修正申請が「承認待ち」かどうかを判定**
    $hasPendingApproval = $adjust && $adjust->status === 'pending';

    // ビューにデータを渡す
    return view('attendance_detail', compact('attendance', 'adjust', 'hasPendingApproval'));
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
        'date' => $fullDate, // ✅ $fullDate に修正
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'break_start_time' => $request->break_start_time,
        'break_end_time' => $request->break_end_time,
        'remarks' => $request->remarks,
    ]);

    return redirect()->route('correctionrequest')->with('message', '修正申請が完了しました。');
    }
}
