<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceListController extends Controller
{
    public function attendance_list()
    {
        // ログインユーザーの勤怠データを取得
        $attendances = Attendance::where('user_id', Auth::id())
                                 ->orderBy('date', 'desc')
                                 ->get();

        // ビューにデータを渡す
        return view('attendance_list', compact('attendances'));
    }
}
