<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;



class AdminListController extends AdminBaseController
{
    public function admin_list(Request $request)
    {


        $redirect = $this->handleRedirects($request);
        if ($redirect) {
            return $redirect;
        }


        $date = $request->query('date', Carbon::today()->toDateString());


        $currentDate = Carbon::parse($date);


        $previousDate = $currentDate->copy()->subDay()->toDateString();
        $nextDate = $currentDate->copy()->addDay()->toDateString();

        
        $attendances = Attendance::with('user') 
            ->whereDate('date', $currentDate) // 
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
