<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Adjust;
use Illuminate\Support\Facades\Auth;

class AttendanceDetailController extends Controller
{
     public function attendancedetail($id)
{
    // 勤怠データを取得
    $attendance = Attendance::with('user')->findOrFail($id);

    // `break_times` が null または空文字の場合、デフォルト値を設定
    if (empty($attendance->break_times)) {
        $attendance->break_times = json_encode([]);
    }

    // ビューにデータを渡して詳細ページを表示
    return view('attendance_detail', compact('attendance'));
}


   public function update(Request $request, $id)
    {
         // 勤怠データの取得（元の日付を取得）
    $attendance = Attendance::findOrFail($id);

    // 年月日を `YYYY-MM-DD` 形式に結合
    $fullDate = sprintf('%04d-%s', $request->year, $request->month_day);

    // 修正データを `adjusts` テーブルに保存（`attendances` テーブルは更新しない）
    Adjust::create([
        'attendance_id' => $id,
        'user_id' => auth()->id(),
        'original_date' => $attendance->date, // 修正前の日付
        'date' => $fullDate, // `YYYY-MM-DD` 形式で保存
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'break_start_time' => $request->break_start_time,
        'break_end_time' => $request->break_end_time,
        'remarks' => $request->remarks,
    ]);

    return redirect()->route('correctionrequest')->with('message', '修正申請が完了しました。');
    }
}
