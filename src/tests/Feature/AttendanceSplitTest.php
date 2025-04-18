<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class AttendanceSplitTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function testCrossDateAttendanceIsSplitCorrectly()
    {
        $user = User::factory()->create();


        $start = Carbon::create(2025, 4, 16, 22, 0, 0);
        $end = Carbon::create(2025, 4, 17, 6, 0, 0);


        $attendance1 = Attendance::create([
            'user_id' => $user->id,
            'date' => $start->toDateString(),
            'start_time' => $start,
            'end_time' => $start->copy()->endOfDay(),
            'break_minutes' => 30,
            'work_minutes' => $start->diffInMinutes($start->copy()->endOfDay()) - 30,
        ]);


        $attendance2 = Attendance::create([
            'user_id' => $user->id,
            'date' => $end->toDateString(),
            'start_time' => $end->copy()->startOfDay(),
            'end_time' => $end,
            'break_minutes' => 15,
            'work_minutes' => $end->copy()->startOfDay()->diffInMinutes($end) - 15,
        ]);


        $attendances = Attendance::where('user_id', $user->id)->orderBy('date')->get();

        $this->assertCount(2, $attendances);


        $this->assertEquals('2025-04-16', $attendances[0]->date->toDateString());
        $this->assertEquals('22:00:00', $attendances[0]->start_time->format('H:i:s'));
        $this->assertEquals('23:59:59', $attendances[0]->end_time->format('H:i:s'));
        $this->assertEquals(30, $attendances[0]->break_minutes);
        $this->assertEquals(89, $attendances[0]->work_minutes); // 120 - 30


        $this->assertEquals('2025-04-17', $attendances[1]->date->toDateString());
        $this->assertEquals('00:00:00', $attendances[1]->start_time->format('H:i:s'));
        $this->assertEquals('06:00:00', $attendances[1]->end_time->format('H:i:s'));
        $this->assertEquals(15, $attendances[1]->break_minutes);
        $this->assertEquals(345, $attendances[1]->work_minutes); // 360 - 15
    }
}
