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

    // 修正後：受け取った $id を使用
    $adjust = Adjust::with(['breakTimes', 'user', 'attendance.user'])->findOrFail($id);
    $attendance = $adjust->attendance;

    $hasPendingApproval = in_array($adjust->status, ['approved', 'pending']);

    return view('admin.application_approval', compact('attendance', 'adjust', 'hasPendingApproval'));
}

    public function approve($id)
    {
        $adjust = Adjust::where('attendance_id', $id)->firstOrFail();

        // すでに承認済みなら処理しない
        if ($adjust->status === 'approved') {
            return redirect()->back()->with('message', '既に承認済みです。');
        }

        // ステータスを `approved` に更新
        $adjust->status = 'approved';
        $adjust->save();

        return redirect()->route('admin.application_request')->with('message', '申請を承認しました。');

    }
}

