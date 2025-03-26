<?php

namespace App\Http\Controllers\Admin;

/*use App\Http\Controllers\Controller;*/
use App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
/*use Illuminate\Support\Facades\Auth;*/


class AdminListController extends AdminBaseController
{
    public function admin_list(Request $request)
    {

        // リダイレクト処理を呼び出し
    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }


    
        // クエリパラメータから `date` を取得し、なければ今日の日付を設定
        $date = $request->query('date', Carbon::today()->toDateString());

        // Carbon インスタンスに変換
        $currentDate = Carbon::parse($date);

        // **前日・翌日を計算**
        $previousDate = $currentDate->copy()->subDay()->toDateString();
        $nextDate = $currentDate->copy()->addDay()->toDateString();

        // **この日付の勤怠データのみ取得**
        $attendances = Attendance::with('user') 
            ->whereDate('date', $currentDate) // ✅ `whereDate()` で指定日のデータ取得
            ->orderBy('start_time', 'asc')
            ->get();

        return view('admin.attendance_list', [
            'currentDate' => $currentDate,  
            'previousDate' => $previousDate, 
            'nextDate' => $nextDate,
            'attendances' => $attendances
        ]);
    }
}
