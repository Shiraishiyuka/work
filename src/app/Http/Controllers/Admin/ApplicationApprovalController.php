<?php

namespace App\Http\Controllers\Admin;

/*use App\Http\Controllers\Controller;*/
use App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Adjust;
/*use Illuminate\Support\Facades\Auth;*/


class ApplicationApprovalController extends AdminBaseController
{
    public function approval(Request $request,$id){

        // リダイレクト処理を呼び出し
    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }

        $attendance = Attendance::with('user')->findOrFail($id);

        // `break_times` が null または空文字の場合、デフォルト値を設定
        if (empty($attendance->break_times)) {
            $attendance->break_times = json_encode([]);
        }

        // 修正申請があるか確認
        $adjust = Adjust::where('attendance_id', $id)->first();

        return view('admin.application_approval', compact('attendance', 'adjust'));
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

        return redirect()->back()->with('message', '申請を承認しました。');
    }
}

