<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\BaseController;


class ByStaffListController extends BaseController
{
    
    public function by_staff(Request $request, $id, $year = null, $month = null){


    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }

        if (!$id) {
        abort(404);
        }

    $user = User::findOrFail($id);


        $year = $request->query('year', $year ?? Carbon::today()->year);
        $month = $request->query('month', $month ?? Carbon::today()->month);


        $currentDate = Carbon::createFromDate($year, $month, 1);


        $previousDate = $currentDate->copy()->subMonth();
        $nextDate = $currentDate->copy()->addMonth();


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

    public function exportCsv(Request $request, $id)
{
    $year = $request->query('year', Carbon::today()->year);
    $month = $request->query('month', Carbon::today()->month);

    $user = User::findOrFail($id);


    $attendances = Attendance::where('user_id', $id)
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->orderBy('date', 'asc')
        ->get();


    $csvHeader = ["日付", "出勤", "退勤", "休憩時間", "勤務時間"];


    $csvData = [];
    foreach ($attendances as $attendance) {
        $csvData[] = [
            $attendance->date,
            $attendance->start_time,
            $attendance->end_time ?? '-',
            floor($attendance->break_minutes / 60) . "時間" . ($attendance->break_minutes % 60) . "分",
            floor($attendance->work_minutes / 60) . "時間" . ($attendance->work_minutes % 60) . "分"
        ];
    }


    $fileName = "{$user->name}_{$year}_{$month}_勤怠.csv";


    $handle = fopen('php://temp', 'r+');
    fputcsv($handle, $csvHeader);
    foreach ($csvData as $row) {
        fputcsv($handle, $row);
    }
    rewind($handle);
    $csvOutput = stream_get_contents($handle);
    fclose($handle);

    return response($csvOutput)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
}
}

