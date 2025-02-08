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
                $attempt = 0; // 試行回数
                do {
                    $date = Carbon::create(2025, 1, rand(1, 30))->toDateString();
                    $attempt++;
                    
                    // 30回試しても新しい日付が見つからなかったらスキップ
                    if ($attempt > 30) {
                        continue 2; // 内側の `do-while` ではなく、forループごと抜ける
                    }
                } while (in_array($date, $usedDates) || Attendance::where('user_id', $user->id)->whereDate('date', $date)->exists());

                $usedDates[] = $date;

                $start_time = Carbon::createFromTime(rand(7, 11), rand(0, 59), 0);
                $end_time = Carbon::createFromTime(rand(16, 21), rand(0, 59), 0);
                $break_minutes_options = [30, 45, 60, 75, 90];
                $break_minutes = $break_minutes_options[array_rand($break_minutes_options)];
                $work_minutes = max(0, $start_time->diffInMinutes($end_time) - $break_minutes);

                DB::table('attendances')->insert([
                    'user_id' => $user->id,
                    'date' => $date,
                    'start_time' => $start_time->format('H:i:s'),
                    'end_time' => $end_time->format('H:i:s'),
                    'break_minutes' => $break_minutes,
                    'work_minutes' => $work_minutes,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
