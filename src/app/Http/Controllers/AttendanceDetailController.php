<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Adjust;
use App\Models\BreakTime;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AttendanceRequest;
use App\Http\Controllers\BaseController;
use Carbon\Carbon;

class AttendanceDetailController extends BaseController
{
     public function attendancedetail($id)
    {
        $attendance = Attendance::with('user', 'breakTimes')->findOrFail($id);

        $adjust = Adjust::where('attendance_id', $id)
            ->where(function ($query) {
                $query->where('status', 'pending')
                      ->orWhere('status', 'approved');
            })
            ->latest()
            ->first();

        $hasPendingApproval = $adjust && $adjust->status === 'pending';

        return view('attendance_detail', compact('attendance', 'adjust', 'hasPendingApproval'));
    }

    public function update(AttendanceRequest $request, $id)
    {
        $attendance = Attendance::findOrFail($id);


        $fullDate = sprintf('%04d-%s', $request->year, $request->month_day);

        $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $fullDate . ' ' . $request->start_time);
        $endDateTime   = Carbon::createFromFormat('Y-m-d H:i', $fullDate . ' ' . $request->end_time);

        $adjust = Adjust::create([
            'attendance_id' => $id,
            'user_id'       => auth()->id(),
            'date'          => $fullDate,
            'start_time'    => $startDateTime,
            'end_time'      => $endDateTime,
            'remarks'       => $request->remarks,
        ]);


        if ($request->has('break_times')) {
            foreach ($request->break_times as $break) {
                if (!empty($break['start_time']) && !empty($break['end_time'])) {
                    $breakStart = Carbon::createFromFormat('Y-m-d H:i', $fullDate . ' ' . $break['start_time']);
                    $breakEnd   = Carbon::createFromFormat('Y-m-d H:i', $fullDate . ' ' . $break['end_time']);


                    if ($breakEnd->lt($breakStart)) {
                        $breakEnd->addDay();
                    }

                    BreakTime::create([
                        'adjust_id'  => $adjust->id,
                        'start_time' => $breakStart,
                        'end_time'   => $breakEnd,
                    ]);
                }
            }
        }

        return redirect()->route('correctionrequest')->with('message', '修正申請が完了しました。');
    }
}
