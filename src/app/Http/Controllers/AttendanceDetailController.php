<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceDetailController extends Controller
{
    public function attendancedetail($id)
    {
        // 指定されたIDの勤怠データを取得
        $attendance = Attendance::findOrFail($id);

        // ビューにデータを渡す
        return view('attendance_detail', compact('attendance'));
    }
}
