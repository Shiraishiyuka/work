<?php

namespace App\Http\Controllers\Admin;

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
    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }

    $attendance = Attendance::with('user')->findOrFail($id);


    $adjust = Adjust::where('attendance_id', $id)
        ->whereIn('status', ['pending', 'approved'])
        ->orderByRaw("FIELD(status, 'pending', 'approved')")
        ->latest()
        ->first();

    $hasPendingApproval = $adjust && $adjust->status === 'pending';


    $break_minutes = 0;
    $break_start = $adjust->break_start_time ?? $attendance->break_start_time;
    $break_end = $adjust->break_end_time ?? $attendance->break_end_time;

    if ($break_start && $break_end) {
        $start = \Carbon\Carbon::parse($break_start);
        $end = \Carbon\Carbon::parse($break_end);
        $break_minutes = $start->diffInMinutes($end);
    }

    return view('admin.admin_attendance', compact('attendance', 'adjust', 'hasPendingApproval', 'break_minutes'));
}

    public function update_attendance(AttendanceRequest $request, $id)
    {

        $attendance = Attendance::findOrFail($id);


        $fullDate = sprintf('%04d-%s', $request->year, $request->month_day);


        Adjust::create([
        'attendance_id' => $id,
        'user_id' => auth()->id(),
        'original_date' => $attendance->date,
        'date' => $fullDate,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'break_start_time' => $request->break_start_time,
        'break_end_time' => $request->break_end_time,
        'remarks' => $request->remarks,
    ]);


        return redirect()->route('admin.application_request')->with('message', '修正申請が完了しました。');
    }
}
