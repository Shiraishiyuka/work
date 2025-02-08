<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class ByStaffListController extends Controller
{
    public function by_staff($id){
        // 指定された `id` のユーザーを取得
    $user = User::findOrFail($id);

    // そのユーザーの勤怠データのみを取得
    $attendances = Attendance::where('user_id', $id)->orderBy('date', 'desc')->get();

    return view('admin.by_staff_list', compact('user', 'attendances'));
    }
}
