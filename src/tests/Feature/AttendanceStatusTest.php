<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceStatusTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

     public function test_attendance_screen_displays_current_datetime()
    {
        $user = User::factory()->create();
        $now = Carbon::now();

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee($now->translatedFormat('Y年m月d日（D）'));
        $response->assertSee($now->format('H:i'));
    }


    public function test_status_displays_not_working()
    {
        $user = User::factory()->create();


        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('勤務外');
    }



    public function test_status_displays_working()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'start_time' => now()->subHours(2)->format('H:i:s'),
            'end_time' => null,
        ]);

        $response = $this->actingAs($user)
                         ->withSession(['attendance_status' => 'working'])
                         ->get('/attendance');

        $response->assertSee('出勤中');
    }

    public function test_status_displays_on_break()
{
    $user = \App\Models\User::factory()->create();


    $attendance = \App\Models\Attendance::create([
        'user_id' => $user->id,
        'date' => now()->toDateString(),
        'start_time' => now()->subHours(2)->format('H:i:s'),
        'end_time' => null,
    ]);


    $response = $this->actingAs($user)
                     ->withSession(['attendance_status' => 'on_break'])
                     ->get('/attendance');

    $response->assertSee('休憩中');
}

public function test_status_displays_finished()
{
    $user = \App\Models\User::factory()->create();


    $attendance = \App\Models\Attendance::create([
        'user_id' => $user->id,
        'date' => now()->toDateString(),
        'start_time' => now()->subHours(8)->format('H:i:s'),
        'end_time' => now()->format('H:i:s'),
    ]);

    $response = $this->actingAs($user)
                     ->withSession(['attendance_status' => 'finished'])
                     ->get('/attendance');

    $response->assertSee('退勤済');
}

}
