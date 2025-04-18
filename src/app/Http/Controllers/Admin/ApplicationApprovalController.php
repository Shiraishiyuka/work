<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Adjust;


class ApplicationApprovalController extends AdminBaseController
{
    public function approval(Request $request, $id)
{

    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }


    $adjust = Adjust::with(['breakTimes', 'user', 'attendance.user'])->findOrFail($id);
    $attendance = $adjust->attendance;

    $hasPendingApproval = in_array($adjust->status, ['approved', 'pending']);

    return view('admin.application_approval', compact('attendance', 'adjust', 'hasPendingApproval'));
}

    public function approve($id)
{
    $adjust = Adjust::where('attendance_id', $id)->where('status', 'pending')->firstOrFail();


    if ($adjust->status === 'approved') {
        return redirect()->back()->with('message', '既に承認済みです。');
    }


    $adjust->status = 'approved';
    $adjust->save();


    Adjust::where('attendance_id', $adjust->attendance_id)
        ->where('id', '!=', $adjust->id)
        ->where('status', 'pending')
        ->delete();

    return redirect()->route('admin.correction_request')->with('message', '申請を承認しました。');
}
}

