<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Adjust;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceRequest;
use App\Http\Controllers\BaseController;

class AttendanceDetailController extends BaseController
{
     public function attendancedetail($id)
{

    $attendance = Attendance::with('user')->findOrFail($id);


    $adjust = Adjust::where('attendance_id', $id)
        ->where('status', 'pending')
        ->orWhere('status', 'approved')
        ->latest()
        ->first();


    $hasPendingApproval = $adjust && $adjust->status === 'pending';


    return view('attendance_detail', compact('attendance', 'adjust', 'hasPendingApproval'));
}


   public function update(AttendanceRequest $request, $id)
    {

    $attendance = Attendance::findOrFail($id);


    $fullDate = sprintf('%04d-%s', $request->year, $request->month_day);


    $adjust = Adjust::create([
    'attendance_id' => $id,
    'user_id' => auth()->id(),
    'date' => $fullDate,
    'start_time' => $request->start_time,
    'end_time' => $request->end_time,
    'remarks' => $request->remarks,
]);


if ($request->has('break_times')) {
    foreach ($request->break_times as $break) {
        if (!empty($break['start_time']) && !empty($break['end_time'])) {
            \App\Models\BreakTime::create([
                'adjust_id' => $adjust->id,
                'start_time' => $break['start_time'],
                'end_time' => $break['end_time'],
            ]);
        }
    }
}

    return redirect()->route('correctionrequest')->with('message', '修正申請が完了しました。');
    }
}
