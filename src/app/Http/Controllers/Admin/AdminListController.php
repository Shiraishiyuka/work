<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminListController extends Controller
{
    public function attendance_list(Request $request, $year = null, $month = null)
{
    if (!$year || !$month) {
        $currentDate = now();
        $year = $currentDate->year;
        $month = $currentDate->month;
    }

    return view('admin.attendance_list', [
        'year' => (int)$year, // 配列ではなく数値として渡す
        'month' => (int)$month
    ]);
}
}
