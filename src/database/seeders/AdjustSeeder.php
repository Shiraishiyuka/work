<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Adjust;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdjustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendances = Attendance::take(10)->get();

        foreach ($attendances as $attendance) {
            $newStart = Carbon::parse($attendance->start_time)->addMinutes(rand(-10, 10));
            $newEnd = $attendance->end_time ? Carbon::parse($attendance->end_time)->addMinutes(rand(-10, 10)) : null;

            $adjust = Adjust::create([
                'attendance_id' => $attendance->id,
                'user_id' => $attendance->user_id,
                'date' => $attendance->date,
                'start_time' => $newStart,
                'end_time' => $newEnd,
                'remarks' => 'テスト用の修正申請です',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
