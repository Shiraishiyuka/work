<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class BreakTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attendances = Attendance::all();

        foreach ($attendances as $attendance) {

            if (!$attendance->start_time || !$attendance->end_time) continue;


            $breakCount = rand(1, 2);
            $start = Carbon::parse($attendance->start_time);
            $end = Carbon::parse($attendance->end_time ?? $start->copy()->endOfDay());

            for ($i = 0; $i < $breakCount; $i++) {
                $breakStart = $start->copy()->addMinutes(rand(60, 180));
                if ($breakStart->gt($end->copy()->subMinutes(30))) break;

                $breakEnd = $breakStart->copy()->addMinutes(rand(15, 30));
                if ($breakEnd->gt($end)) $breakEnd = $end;

                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'start_time' => $breakStart,
                    'end_time' => $breakEnd,
                ]);
            }
        }
    }

}
