<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Adjust;
use Illuminate\Support\Facades\Auth;


class ApplicationApprovalController extends Controller
{
    public function approval(Request $request,$id){

        $attendance = Attendance::with('user')->findOrFail($id);

        // `break_times` が null または空文字の場合、デフォルト値を設定
        if (empty($attendance->break_times)) {
            $attendance->break_times = json_encode([]);
        }

        return view('admin.application_approval', compact('attendance'));
    }
}

