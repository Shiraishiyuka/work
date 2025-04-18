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
            'user_id' => User::factory(),
            'date' => Carbon::today()->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '18:00:00',
            'break_minutes' => 60,
            'work_minutes' => 480,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
