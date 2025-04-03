<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // 関連ユーザーも自動生成
            'date' => Carbon::today()->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'break_start_time' => '12:00:00',
            'break_end_time' => '13:00:00',
            'break_minutes' => 60,
            'work_minutes' => 480,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
