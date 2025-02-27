<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adjust;
use Carbon\Carbon;
use App\Http\Controllers\AdminBaseController;


class ApplicationRequestController extends AdminBaseController
{
    public function application_request(Request $request, $year = null, $month = null){

        // リダイレクト処理を呼び出し
    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }


         // `year` と `month` が null の場合、現在の年月をセット
        $year = $year ?? Carbon::now()->year;
        $month = $month ?? Carbon::now()->month;

        // `Carbon` インスタンスを作成
        $currentDate = Carbon::createFromDate($year, $month, 1);

        // **前月・次月の計算**
        $previousMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();

        // **指定された年月の申請データを取得**
        $adjustments = Adjust::with('user')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.correction_request', [
            'adjustments' => $adjustments,
            'currentDate' => $currentDate,
            'previousYear' => $previousMonth->year,
            'previousMonth' => $previousMonth->month,
            'nextYear' => $nextMonth->year,
            'nextMonth' => $nextMonth->month
        ]);

    }

    
}
