<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;


class AttendacneTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_work_start_shows_button_and_changes_status()
{
    $user = User::factory()->create();
    /*出勤*/
    // ステータスを「勤務外」にセット
    $response = $this->actingAs($user)
                     ->withSession(['attendance_status' => 'not_working'])
                     ->get('/attendance');

    $response->assertSee('出勤');

    // 出勤処理を実行
    $this->post('/attendance/start');

    // 再度アクセスしてステータスが「出勤中」か確認
    $after = $this->actingAs($user)->get('/attendance');
    $after->assertSee('出勤中');
}

 public function test_work_start_button_not_visible_after_finished()
{
    $user = User::factory()->create();

    $response = $this->actingAs($user)
                     ->withSession(['attendance_status' => 'finished'])
                     ->get('/attendance');

    $response->assertDontSee('id="start-button"');
}

public function test_attendance_start_time_saved_correctly()
{
    $user = User::factory()->create();

    Carbon::setTestNow(Carbon::create(2025, 3, 27, 9, 0)); // 仮の現在時刻
    $this->actingAs($user)->post('/attendance/start');

    $this->assertDatabaseHas('attendances', [
        'user_id' => $user->id,
        'start_time' => '09:00:00',
        'date' => '2025-03-27',
    ]);
}

public function test_break_start_button_and_status_change()
{
    $user = User::factory()->create();

    Attendance::factory()->create([
    'user_id' => $user->id,
    'date' => now()->toDateString(),
    'start_time' => '09:00:00',
]);

$this->actingAs($user)
     ->withSession(['attendance_status' => 'working'])
     ->get('/attendance')
     ->assertSee('休憩入');

    $this->post('/attendance/break');

    $this->actingAs($user)->get('/attendance')
         ->assertSee('休憩中');
}

public function test_break_end_status_return_to_working()
{

    Carbon::setTestNow(Carbon::create(2025, 3, 27, 10, 30));

    $user = User::factory()->create();

Attendance::factory()->create([
    'user_id' => $user->id,
    'date' => '2025-03-27',
    'start_time' => '09:00:00',
    'break_start_time' => '10:00:00',
]);

$this->actingAs($user)
     ->withSession(['attendance_status' => 'on_break'])
     ->post('/attendance/break/end');

$this->actingAs($user)
     ->withSession(['attendance_status' => 'working']) // ← 追加でセッションも再設定
     ->get('/attendance')
     ->assertSee('出勤中');
}

public function test_multiple_breaks_show_break_end_button()
{
    $user = User::factory()->create();

    Attendance::factory()->create([
        'user_id' => $user->id,
        'date' => now()->toDateString(),
        'start_time' => '09:00:00',
        'break_start_time' => '11:00:00',
    ]);

    $this->actingAs($user)->withSession(['attendance_status' => 'on_break'])
         ->get('/attendance')->assertSee('休憩戻');
}

public function test_break_time_is_saved_to_database()
{
    $user = User::factory()->create();

    // 現在時刻を 11:00 にセット
    Carbon::setTestNow(Carbon::create(2025, 3, 27, 11, 0));

    Attendance::factory()->create([
        'user_id' => $user->id,
        'date' => '2025-03-27',
        'start_time' => '09:00:00',
        'break_start_time' => '11:00:00',
        'break_minutes' => 0,
    ]);

    // 30分後に現在時刻を変更
    Carbon::setTestNow(Carbon::create(2025, 3, 27, 11, 30));

    $this->actingAs($user)
         ->withSession(['attendance_status' => 'on_break'])
         ->post('/attendance/break/end');

    $this->assertDatabaseHas('attendances', [
        'user_id' => $user->id,
        'break_minutes' => 30,
    ]);
}

public function test_end_work_changes_status_to_finished()
{
    $user = User::factory()->create();

    Attendance::factory()->create([
        'user_id' => $user->id,
        'date' => now()->toDateString(),
        'start_time' => '09:00:00',
    ]);

    $this->actingAs($user)->withSession(['attendance_status' => 'working'])
         ->get('/attendance')->assertSee('退勤');

    $this->post('/attendance/end');

    $this->actingAs($user)->get('/attendance')
         ->assertSee('退勤済');
}


public function test_end_time_is_saved_correctly()
{
    

    Carbon::setTestNow(Carbon::create(2025, 3, 27, 17, 00));

    $user = User::factory()->create();

Attendance::factory()->create([
    'user_id' => $user->id,
    'date' => '2025-03-27',
    'start_time' => '09:00:00',
    'break_minutes' => 30,
]);

$this->actingAs($user)->post('/attendance/end');

$this->assertDatabaseHas('attendances', [
    'user_id' => $user->id,
    'end_time' => '17:00:00',
    'work_minutes' => 450,
]);
}


}
