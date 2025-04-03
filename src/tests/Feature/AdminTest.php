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

        // 管理者ユーザーを作成
        $this->admin = User::factory()->create([
            'name' => '管理者',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => true,
        ]);
    }

    /** 勤怠一覧に今日の日付が表示される */
    public function test_attendance_list_shows_today()
    {
        $today = Carbon::now()->format('Y年m月d日');

        $response = $this->actingAs($this->admin)->get('/admin/attendance/list');
        $response->assertSee($today);
    }

    /** 前日ボタンで前日の勤怠が見れる */
    public function test_attendance_list_shows_previous_day()
    {
        $yesterday = Carbon::now()->subDay()->toDateString();

        $response = $this->actingAs($this->admin)->get("/admin/attendance/list?date={$yesterday}");
        $response->assertSee(Carbon::parse($yesterday)->format('Y年m月d日'));
    }

    /** 翌日ボタンで翌日の勤怠が見れる */
    public function test_attendance_list_shows_next_day()
    {
        $tomorrow = Carbon::now()->addDay()->toDateString();

        $response = $this->actingAs($this->admin)->get("/admin/attendance/list?date={$tomorrow}");
        $response->assertSee(Carbon::parse($tomorrow)->format('Y年m月d日'));
    }

    /** 勤怠詳細ページで正しい内容が表示される */
    public function test_admin_can_view_attendance_detail()
    {
        $user = User::factory()->create();
        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin_attendance', ['id' => $attendance->id]));

        $response->assertSee($user->name);
        $response->assertSee($attendance->start_time);
    }

    /** 出勤 > 退勤 バリデーションチェック */
    public function test_admin_attendance_validation_start_after_end()
{
    $attendance = Attendance::factory()->create();

    $response = $this->from(route('admin_attendance', ['id' => $attendance->id]))
        ->actingAs($this->admin)
        ->post(route('admin.update', ['id' => $attendance->id]), [
            'start_time' => '18:00',
            'end_time' => '09:00',
            'remarks' => 'テスト備考'
        ]);

    $response->assertSessionHasErrors(['start_time']);
}

public function test_admin_break_start_after_end()
{
    $attendance = Attendance::factory()->create();

    $response = $this->from(route('admin_attendance', ['id' => $attendance->id]))
        ->actingAs($this->admin)
        ->post(route('admin.update', ['id' => $attendance->id]), [
            'start_time' => '09:00',
            'end_time' => '18:00',
            'break_start_time' => '19:00',
            'break_end_time' => '20:00',
            'remarks' => 'テスト備考'
        ]);

    $response->assertSessionHasErrors(['break_start_time']);
}

public function test_admin_break_end_after_end()
{
    $attendance = Attendance::factory()->create();

    $response = $this->from(route('admin_attendance', ['id' => $attendance->id]))
        ->actingAs($this->admin)
        ->post(route('admin.update', ['id' => $attendance->id]), [
            'start_time' => '09:00',
            'end_time' => '18:00',
            'break_start_time' => '15:00',
            'break_end_time' => '19:00',
            'remarks' => 'テスト備考'
        ]);

    $response->assertSessionHasErrors(['break_end_time']);
}

public function test_admin_remarks_required_validation()
{
    $attendance = Attendance::factory()->create();

    $response = $this->from(route('admin_attendance', ['id' => $attendance->id]))
        ->actingAs($this->admin)
        ->post(route('admin.update', ['id' => $attendance->id]), [
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
        $response->assertSee($user->email);
    }
}

public function test_admin_sees_user_attendance_by_staff()
{
    $user = User::factory()->create();
    $attendance = Attendance::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($this->admin)->get(route('by_staff', ['id' => $user->id]));
    $response->assertSee($attendance->start_time);
}

public function test_admin_sees_previous_month_applications()
{
    $user = User::factory()->create();
    $attendance = Attendance::factory()->create(['user_id' => $user->id]);
    $lastMonth = Carbon::now()->subMonth();

    $adjust = Adjust::factory()->create([
        'user_id' => $user->id,
        'attendance_id' => $attendance->id,
        'created_at' => $lastMonth,
        'remarks' => 'これは前月の申請理由です',
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('admin.application_request', ['year' => $lastMonth->year, 'month' => $lastMonth->month]));

    $response->assertSee('これは前月の申請理由です');
}

public function test_admin_sees_next_month_applications()
{
    $user = User::factory()->create();
    $attendance = Attendance::factory()->create(['user_id' => $user->id]);
    $nextMonth = Carbon::now()->addMonth();

    $adjust = Adjust::factory()->create([
        'user_id' => $user->id,
        'attendance_id' => $attendance->id,
        'created_at' => $nextMonth,
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('admin.application_request', ['year' => $nextMonth->year, 'month' => $nextMonth->month]));

    $response->assertSee($adjust->remarks);
}

public function test_admin_sees_pending_adjustments()
{
    $adjust = Adjust::factory()->create(['status' => 'pending']);

    $response = $this->actingAs($this->admin)->get(route('admin.application_request'));
    $response->assertSee('承認待ち');
}

public function test_admin_sees_approved_adjustments()
{
    $adjust = Adjust::factory()->create(['status' => 'approved']);

    $response = $this->actingAs($this->admin)->get(route('admin.application_request'));
    $response->assertSee('承認済み');
}

public function test_admin_sees_adjustment_detail()
{
    $adjust = Adjust::factory()->create();

    $response = $this->actingAs($this->admin)->get(route('approval', ['attendance_correct_request' => $adjust->attendance_id]));
    $response->assertSee($adjust->remarks);
}

public function test_admin_can_approve_adjustment()
{
    $adjust = Adjust::factory()->create(['status' => 'pending']);

    $response = $this->actingAs($this->admin)->post(route('admin.application.approve', ['id' => $adjust->attendance_id]));
    $response->assertRedirect();

    $this->assertDatabaseHas('adjusts', [
        'id' => $adjust->id,
        'status' => 'approved',
    ]);
}

public function test_admin_sees_all_attendance_info_for_today()
{
    $users = User::factory()->count(3)->create();
    foreach ($users as $user) {
        Attendance::factory()->create([
            'user_id' => $user->id,
            'start_time' => '09:00',
            'end_time' => '18:00',
        ]);
    }

    $response = $this->actingAs($this->admin)->get('/admin/attendance/list');

    foreach ($users as $user) {
        $response->assertSee($user->name);
        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }
}

public function test_admin_can_navigate_to_attendance_detail()
{
    $user = User::factory()->create();
    $attendance = Attendance::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($this->admin)->get('/admin/attendance/list');
    $response->assertSee(route('admin_attendance', ['id' => $attendance->id])); // 詳細リンクの確認
}

}
