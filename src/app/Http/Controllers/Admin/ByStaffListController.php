<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;


class ByStaffListController extends BaseController
{
    
    public function by_staff(Request $request, $id, $year = null, $month = null){

        // リダイレクト処理を呼び出し
    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }

        if (!$id) {
        abort(404); // `id` がない場合は404エラー
        }
        // 指定された `id` のユーザーを取得
    $user = User::findOrFail($id);

    // URLの `year` と `month` のパラメータを取得し、デフォルトを設定
        $year = $request->query('year', $year ?? Carbon::today()->year);
        $month = $request->query('month', $month ?? Carbon::today()->month);

        // **Carbon インスタンスに変換**
        $currentDate = Carbon::createFromDate($year, $month, 1);

        // **前月・次月を計算**
        $previousDate = $currentDate->copy()->subMonth();
        $nextDate = $currentDate->copy()->addMonth();

    // そのユーザーの勤怠データのみを取得
    $attendances = Attendance::where('user_id', $id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'asc')
            ->get();

    return view('admin.by_staff_list', [
            'user' => $user,
            'currentDate' => $currentDate,
            'previousDate' => $previousDate, 
            'nextDate' => $nextDate,
            'attendances' => $attendances,
            'year' => $year,
            'month' => $month
        ]);
    }
}

