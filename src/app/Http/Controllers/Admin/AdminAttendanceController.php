<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Adjust;
use Illuminate\Support\Facades\Auth;

class AdminAttendanceController extends Controller
{
    public function admin_attendance(Request $request,$id){
        $attendance = Attendance::with('user')->findOrFail($id);

        // `break_times` が null または空文字の場合、デフォルト値を設定
        if (empty($attendance->break_times)) {
            $attendance->break_times = json_encode([]);
        }

        return view('admin_attendance', compact('attendance'));
    }

     public function update_attendance(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        // `user_id` を取得（`request` から取得し、ない場合は `attendance` の `user_id` を使用）
        $userId = $request->input('user_id', $attendance->user_id);

        if (!$userId) {
        return redirect()->back()->withErrors(['user_id' => 'ユーザーIDが見つかりませんでした。']);
        }

        // 修正データを `adjusts` テーブルに保存（変更履歴を記録）
        Adjust::create([
            'attendance_id' => $id,
            'user_id' => $userId, // 従業員のIDをセット
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_minutes' => $request->break_minutes,
            'remarks' => $request->remarks,
        ]);

        /* `attendances` テーブルのデータも更新
        $attendance->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_minutes' => $request->break_minutes,
        ]);
        */

        return redirect()->route('admin.application_request', ['id' => $id]);
    }
}