<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Adjust;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Carbon;

class AdjustFactory extends Factory
{
    protected $model = Adjust::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);
        return [
            'user_id' => $user->id,
            'attendance_id' => $attendance->id,
            'remarks' => $this->faker->sentence,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
