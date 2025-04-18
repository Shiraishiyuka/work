<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\BreakTime;
use Carbon\Carbon;

class AttendanceControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use RefreshDatabase;

    public function test_cross_date_break_and_work_time_calculation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // 出勤（前日）
        Carbon::setTestNow(Carbon::create(2025, 4, 10, 22, 0, 0));
        $this->post('/attendance/start');

        // 休憩開始（23:00）
        Carbon::setTestNow(Carbon::create(2025, 4, 10, 23, 0, 0));
        $this->post('/attendance/break');

        // 休憩終了（翌日0:00）
        Carbon::setTestNow(Carbon::create(2025, 4, 11, 0, 0, 0));
        $this->post('/attendance/break/end');

        // 退勤（2:00）
        Carbon::setTestNow(Carbon::create(2025, 4, 11, 2, 0, 0));
        $this->post('/attendance/end');

        $attendance = Attendance::first();

        $this->assertEquals('22:00:00', Carbon::parse($attendance->start_time)->format('H:i:s'));
        $this->assertEquals('02:00:00', Carbon::parse($attendance->end_time)->format('H:i:s'));

        $this->assertEquals(60, $attendance->break_minutes);
        $this->assertEquals(180, $attendance->work_minutes);

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'break_minutes' => 60,
            'work_minutes' => 180,
        ]);
    }
}
