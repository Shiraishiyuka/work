<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\Adjust;

class AdminTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
        ]);
    }

    public function test_attendance_list_shows_today()
    {
        $today = Carbon::now()->format('Y年m月d日');

        $response = $this->actingAs($this->admin)->get('/admin/attendance/list');
        $response->assertSee($today);
    }

    public function test_attendance_list_shows_previous_day()
    {
        $yesterday = Carbon::yesterday()->format('Y年m月d日');

        $response = $this->actingAs($this->admin)->get('/admin/attendance/list?date=' . Carbon::yesterday()->toDateString());
        $response->assertSee($yesterday);
    }

    public function test_attendance_list_shows_next_day()
    {
        $tomorrow = Carbon::tomorrow()->format('Y年m月d日');

        $response = $this->actingAs($this->admin)->get('/admin/attendance/list?date=' . Carbon::tomorrow()->toDateString());
        $response->assertSee($tomorrow);
    }

    public function test_admin_can_view_attendance_detail()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($this->admin)->get(route('admin_attendance', ['id' => $attendance->id]));

        $response->assertSee($user->name);
        $response->assertSee($attendance->start_time);
    }

    public function test_admin_attendance_validation_start_after_end()
    {
        $attendance = Attendance::factory()->create();

        $response = $this->from(route('admin_attendance', $attendance->id))
            ->actingAs($this->admin)
            ->post(route('admin.update', $attendance->id), [
                'start_time' => '18:00',
                'end_time' => '09:00',
                'remarks' => 'テスト備考',
            ]);

        $response->assertSessionHasErrors(['start_time']);
    }

    public function test_admin_break_start_after_end()
    {
        $attendance = Attendance::factory()->create();

        $response = $this->from(route('admin_attendance', $attendance->id))
            ->actingAs($this->admin)
            ->post(route('admin.update', $attendance->id), [
                'start_time' => '09:00',
                'end_time' => '18:00',
                'remarks' => 'テスト備考',
                'break_times' => [
                    ['start_time' => '20:00', 'end_time' => '19:00'],
                ],
            ]);

        $response->assertSessionHasErrors(['break_times.0.start_time']);
    }

    public function test_admin_break_end_after_end()
    {
        $attendance = Attendance::factory()->create();

        $response = $this->from(route('admin_attendance', $attendance->id))
            ->actingAs($this->admin)
            ->post(route('admin.update', $attendance->id), [
                'start_time' => '09:00',
                'end_time' => '18:00',
                'remarks' => 'テスト備考',
                'break_times' => [
                    ['start_time' => '15:00', 'end_time' => '19:00'],
                ],
            ]);

        $response->assertSessionHasErrors(['break_times.0.end_time']);
    }

    public function test_admin_remarks_required_validation()
    {
        $attendance = Attendance::factory()->create();

        $response = $this->from(route('admin_attendance', $attendance->id))
            ->actingAs($this->admin)
            ->post(route('admin.update', $attendance->id), [
                'start_time' => '09:00',
                'end_time' => '18:00',
                'remarks' => '', // 空欄でバリデーション
            ]);

        $response->assertSessionHasErrors(['remarks']);
    }

    public function test_admin_sees_all_users_on_staff_list()
    {
        $users = User::factory()->count(3)->create(['is_admin' => false]);

        $response = $this->actingAs($this->admin)->get(route('admin.staff_list'));
        foreach ($users as $user) {
            $response->assertSee($user->name);
        }
    }

    public function test_admin_sees_user_attendance_by_staff()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($this->admin)->get(route('by_staff', $user->id));
        $response->assertSee($attendance->start_time);
    }

    public function test_admin_can_navigate_to_attendance_detail()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($this->admin)->get('/admin/attendance/list');
        $response->assertSee(route('admin_attendance', $attendance->id));
    }
}
