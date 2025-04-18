<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\BreakTime;
use App\Models\Attendance;


class AttendanceController extends BaseController
{


 public function show(Request $request)
    {
        $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }

        Carbon::setLocale('ja');
        $attendance = $this->getTodayOrYesterdayAttendance();

        if (!$attendance) {
            session(['attendance_status' => 'not_working']);
        } elseif (!$attendance->end_time) {
            $latestBreak = $attendance->breakTimes()
                ->whereNull('end_time')
                ->latest()
                ->first();

            session(['attendance_status' => $latestBreak ? 'on_break' : 'working']);
        } else {
            session(['attendance_status' => 'finished']);
        }

        return view('attendance', [
            'currentDateTime' => Carbon::now(),
            'status' => session('attendance_status'),
        ]);
    }

    protected function getTodayOrYesterdayAttendance()
    {
        return Attendance::where('user_id', auth()->id())
            ->where(function ($q) {
                $q->whereDate('date', Carbon::today())
                  ->orWhereDate('date', Carbon::yesterday());
            })
            ->latest('start_time')
            ->first();
    }

    public function startWork(Request $request)
    {
        session(['attendance_status' => 'working']);

        Attendance::create([
            'user_id'    => auth()->id(),
            'date'       => Carbon::today()->toDateString(),
            'start_time' => Carbon::now(),
        ]);

        return redirect()->route('attendance.show');
    }

    public function takeBreak(Request $request)
    {
        session(['attendance_status' => 'on_break']);

        $attendance = $this->getTodayOrYesterdayAttendance();
        if (!$attendance) return redirect()->route('attendance.show');

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'start_time'    => Carbon::now(),
        ]);

        return redirect()->route('attendance.show');
    }

    public function endBreak(Request $request)
    {
        session(['attendance_status' => 'working']);

        $attendance = $this->getTodayOrYesterdayAttendance();
        if (!$attendance) return redirect()->route('attendance.show');

        $break = $attendance->breakTimes()->whereNull('end_time')->latest()->first();
        if ($break) {
            $break->end_time = Carbon::now();
            $break->save();
        }

        $this->recalculateBreakMinutes($attendance);
        return redirect()->route('attendance.show');
    }


    protected function calculateBreakMinutesBetween($attendance, $start, $end)
{
    $total = 0;
    foreach ($attendance->breakTimes()->whereNotNull('end_time')->get() as $bt) {
        $btStart = Carbon::parse($bt->start_time);
        $btEnd = Carbon::parse($bt->end_time);
        if ($btEnd->lt($btStart)) $btEnd->addDay();


        if ($btEnd <= $start || $btStart >= $end) continue;


        $rangeStart = $btStart->greaterThan($start) ? $btStart : $start;
        $rangeEnd = $btEnd->lessThan($end) ? $btEnd : $end;
        $total += $rangeStart->diffInMinutes($rangeEnd);
    }
    return $total;
}


public function endWork(Request $request)
{
    session(['attendance_status' => 'finished']);

    $attendance = $this->getTodayOrYesterdayAttendance();
    if (!$attendance) {
        return redirect()->route('attendance.show');
    }

    $end = Carbon::now();
    $attendance->end_time = null;
    $attendance->save();

    $start = Carbon::parse($attendance->start_time);

    if ($start->isSameDay($end)) {

        $this->recalculateBreakMinutes($attendance);
        $workMinutes = $start->diffInMinutes($end) - $attendance->break_minutes;
        $attendance->end_time = $end;
        $attendance->work_minutes = max(0, $workMinutes);
        $attendance->save();
    } else {



        $midnight = $start->copy()->endOfDay();
        $break1 = $this->calculateBreakMinutesBetween($attendance, $start, $midnight);
        $attendance->break_minutes = $break1;
        $attendance->work_minutes = max(0, $start->diffInMinutes($midnight) - $break1);
        $attendance->save();


        $startNext = $end->copy()->startOfDay();
        $break2 = $this->calculateBreakMinutesBetween($attendance, $startNext, $end);

        Attendance::create([
            'user_id' => $attendance->user_id,
            'date' => $startNext->toDateString(),
            'start_time' => $startNext,
            'end_time' => $end,
            'break_minutes' => $break2,
            'work_minutes' => max(0, $startNext->diffInMinutes($end) - $break2),
            'remarks' => $attendance->remarks,
            'status' => 'pending',
        ]);
    }

    return redirect()->route('attendance.show');
}


/*

    public function endWork(Request $request)
{
    session(['attendance_status' => 'finished']);

    $attendance = $this->getTodayOrYesterdayAttendance();
    if (!$attendance) {
        return redirect()->route('attendance.show');
    }

    $end = Carbon::now(); // 現在の退勤時間
    $attendance->end_time = null; // ✅ NULL にする
    $attendance->save();

    $start = Carbon::parse($attendance->start_time);

    if ($start->isSameDay($end)) {
        // 🔸 同日 → 通常処理
        $this->recalculateBreakMinutes($attendance);
        $workMinutes = $start->diffInMinutes($end) - $attendance->break_minutes;
        $attendance->end_time = $end; // 同日なので退勤も保存
        $attendance->work_minutes = max(0, $workMinutes);
        $attendance->save();
    } else {
        // 🔸 日付をまたいだ場合

        // ① 先に16日の分だけ更新（end_timeはNULL）
        $midnight = $start->copy()->endOfDay();
        $break1 = $this->calculateBreakMinutesBetween($attendance, $start, $midnight);
        $attendance->break_minutes = $break1;
        $attendance->work_minutes = max(0, $start->diffInMinutes($midnight) - $break1);
        $attendance->save();

        // ② 翌日のレコードを新規作成
        $startNext = $end->copy()->startOfDay();
        $break2 = $this->calculateBreakMinutesBetween($attendance, $startNext, $end);

        Attendance::create([
            'user_id' => $attendance->user_id,
            'date' => $startNext->toDateString(),
            'start_time' => $startNext,
            'end_time' => $end,
            'break_minutes' => $break2,
            'work_minutes' => max(0, $startNext->diffInMinutes($end) - $break2),
            'remarks' => $attendance->remarks,
            'status' => 'pending',
        ]);
    }

    return redirect()->route('attendance.show');
}

*/

    protected function recalculateBreakMinutes(Attendance $attendance)
    {
        $total = 0;
        foreach ($attendance->breakTimes()->whereNotNull('end_time')->get() as $bt) {
            $s = Carbon::parse($bt->start_time);
            $e = Carbon::parse($bt->end_time);
            if ($e->lt($s)) $e->addDay();
            $total += $s->diffInMinutes($e);
        }

        $attendance->break_minutes = $total;
        $attendance->save();
    }

 /*前回の
   public function show(Request $request)
{
    $redirect = $this->handleRedirects($request);
    if ($redirect) {
        return $redirect;
    }

    Carbon::setLocale('ja');

    $attendance = $this->getTodayOrYesterdayAttendance(); // ← 変更点

    if (!$attendance) {
        session(['attendance_status' => 'not_working']);
    } elseif (!$attendance->end_time) {
        // 出勤済みで退勤していない
        $latestBreak = $attendance->breakTimes()
            ->whereNull('end_time')
            ->latest()
            ->first();

        if ($latestBreak) {
            session(['attendance_status' => 'on_break']);
        } else {
            session(['attendance_status' => 'working']);
        }
    } else {
        session(['attendance_status' => 'finished']);
    }

    $currentDateTime = Carbon::now();
    $status = session('attendance_status');

    return view('attendance', [
        'currentDateTime' => $currentDateTime,
        'status' => $status,
    ]);
}

    protected function getTodayOrYesterdayAttendance()
    {
        return Attendance::where('user_id', auth()->id())
            ->where(function ($query) {
                $query->whereDate('date', Carbon::today())
                    ->orWhereDate('date', Carbon::yesterday());
            })
            ->latest('start_time')
            ->first();
    }

    public function startWork(Request $request)
    {
        session(['attendance_status' => 'working']);

        $attendance = new Attendance();
        $attendance->user_id = auth()->id();
        $attendance->date = Carbon::today()->format('Y-m-d');
        $attendance->start_time = Carbon::now();
        $attendance->save();

        return redirect()->route('attendance.show');
    }


    public function takeBreak(Request $request)
    {
        session(['attendance_status' => 'on_break']);

        $attendance = Attendance::where('user_id', auth()->id())
            ->whereDate('date', Carbon::today())
            ->first();

        if (!$attendance) {
            return redirect()->route('attendance.show')->withErrors('出勤記録が見つかりません');
        }

        BreakTime::create([
            'attendance_id' => $attendance->id,
            'start_time' => Carbon::now(), 
        ]);

        return redirect()->route('attendance.show');
    }



    public function endBreak(Request $request)
    {
        $attendance = $this->getTodayOrYesterdayAttendance();

        if (!$attendance) {
            return redirect()->route('attendance.show')->withErrors('出勤記録が見つかりません');
        }

        session(['attendance_status' => 'working']);

        $break = $attendance->breakTimes()
            ->whereNull('end_time')
            ->latest()
            ->first();

        if ($break) {
            $start = Carbon::parse($break->start_time);
            $end = Carbon::now();

            $break->end_time = Carbon::now();
            $break->save();

            $breakTimes = $attendance->breakTimes()->whereNotNull('end_time')->get();

$totalBreak = 0;

foreach ($breakTimes as $bt) {
    $start = Carbon::parse($bt->start_time);
    $end = Carbon::parse($bt->end_time);

    if ($end->lt($start)) {
        $end->addDay();
    }

    $totalBreak += $start->diffInMinutes($end);
}

$attendance->break_minutes = $totalBreak;


            $attendance->break_minutes = $totalBreak;
            $attendance->save();
        }

        return redirect()->route('attendance.show');
    }


public function endWork(Request $request)
{
    $attendance = $this->getTodayOrYesterdayAttendance();

    if (!$attendance) {
        return redirect()->route('attendance.show')->withErrors('出勤記録が見つかりません');
    }

    session(['attendance_status' => 'finished']);

    $endTime = Carbon::now();
    $attendance->end_time = $endTime;

    $startTime = Carbon::parse($attendance->start_time);
    $endTime = Carbon::parse($attendance->end_time);

    $breakTimes = $attendance->breakTimes()->whereNotNull('end_time')->get();

    $totalBreak = 0;

    foreach ($breakTimes as $bt) {
        $s = Carbon::parse($bt->start_time);
        $e = Carbon::parse($bt->end_time);

        if ($e->lt($s)) {
            $e->addDay();
        }

        $totalBreak += $s->diffInMinutes($e);
    }

    $attendance->break_minutes = $totalBreak;

    $workMinutes = $startTime->diffInMinutes($endTime) - $totalBreak;
    $attendance->work_minutes = max(0, $workMinutes);

    $attendance->save();

    return redirect()->route('attendance.show');
}
    */

}