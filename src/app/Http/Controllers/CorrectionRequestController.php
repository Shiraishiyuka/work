<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adjust;
/*use App\Models\Attendance;*/
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminBaseController;

class CorrectionRequestController extends AdminBaseController
{
    public function correctionrequest(Request $request) {

        // リダイレクト処理を呼び出し
    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }

        // URL パラメータから `status` を取得（デフォルトは `pending`）
        $status = $request->query('status', 'pending');

        // `status` に基づいてデータを取得
        $adjustments = Adjust::where('user_id', Auth::id())
            ->where('status', $status) // 🔹 ここで `status` をフィルタリング
            ->orderBy('created_at', 'desc')
            ->get();

        return view('correctionrequest', compact('adjustments', 'status'));
    }
}
