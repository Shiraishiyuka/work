<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // ← これを追加！
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;


class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    $users = User::all();

    foreach ($users as $user) {
        $usedDates = [];

        for ($i = 0; $i < 20; $i++) {
            $attempt = 0;
            do {
                $date = Carbon::create(2025, 1, rand(1, 30))->startOfDay();
                $attempt++;
                if ($attempt > 30) {
                    continue 2;
                }
            } while (
                in_array($date->toDateString(), $usedDates) ||
                Attendance::where('user_id', $user->id)->whereDate('date', $date)->exists()
            );

            $usedDates[] = $date->toDateString();

            $start_time = $date->copy()->addHours(22)->addMinutes(rand(0, 59));
            $end_time = $date->copy()->addDay()->addHours(rand(6, 8))->addMinutes(rand(0, 59));


            $break_minutes = rand(30, 60);
            $break1 = intdiv($break_minutes, 2);
            $break2 = $break_minutes - $break1;


            $midnight = $start_time->copy()->endOfDay();
            $work1 = $start_time->diffInMinutes($midnight) - $break1;

            DB::table('attendances')->insert([
                'user_id' => $user->id,
                'date' => $start_time->toDateString(),
                'start_time' => $start_time,
                'end_time' => null,
                'break_minutes' => $break1,
                'work_minutes' => max(0, $work1),
                'remarks' => '夜勤データ（前半）',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            
            $start_next = $end_time->copy()->startOfDay();
            $work2 = $start_next->diffInMinutes($end_time) - $break2;

            DB::table('attendances')->insert([
                'user_id' => $user->id,
                'date' => $end_time->toDateString(),
                'start_time' => $start_next,
                'end_time' => $end_time,
                'break_minutes' => $break2,
                'work_minutes' => max(0, $work2),
                'remarks' => '夜勤データ（後半）',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
}
