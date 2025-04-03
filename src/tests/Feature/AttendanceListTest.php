<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;


class AttendanceListTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected $user;
    protected $today;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->today = Carbon::today();

        // 勤怠データを3日分登録
        foreach (range(0, 2) as $i) {
            Attendance::create([
                'user_id' => $this->user->id,
                'date' => $this->today->copy()->subDays($i),
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'break_minutes' => 60,
                'work_minutes' => 480
            ]);
        }
    }

    /** 勤怠一覧に自分のデータが全て表示される */
    public function test_attendance_list_displays_all_user_data()
    {
        $response = $this->actingAs($this->user)->get('/attendance_list');

        $response->assertStatus(200);
        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }

    /** 現在の月が表示されている */
    public function test_current_month_is_displayed()
    {
        $response = $this->actingAs($this->user)->get('/attendance_list');

        $expectedMonth = $this->today->format('n'); // 3月など
        $response->assertSee("{$expectedMonth}月");
    }

    /** 「前月」ボタンで前月のデータが見られる */
    public function test_previous_month_data_can_be_viewed()
    {
        $lastMonth = $this->today->copy()->subMonth();
        $response = $this->actingAs($this->user)->get('/attendance_list?year=' . $lastMonth->year . '&month=' . $lastMonth->month);

        $response->assertStatus(200);
        $response->assertSee("{$lastMonth->month}月");
    }

    /** 「詳細」ボタンから詳細画面に遷移する */
    public function test_detail_button_navigates_to_detail_page()
    {
        $attendance = Attendance::first();
        $response = $this->actingAs($this->user)->get("/attendancedetail/{$attendance->id}");

        $response->assertStatus(200);
        $response->assertSee('出勤'); // 出勤・退勤ラベルなど
    }

    /** 詳細画面：名前が表示されている */
    public function test_detail_page_displays_user_name()
    {
        $attendance = Attendance::first();
        $response = $this->actingAs($this->user)->get("/attendancedetail/{$attendance->id}");

        $response->assertSee($this->user->name);
    }

    /** 詳細画面：日付が表示されている */
    public function test_detail_page_displays_correct_date()
    {
        $attendance = Attendance::first();
        $response = $this->actingAs($this->user)->get("/attendancedetail/{$attendance->id}");

        $response->assertSee($attendance->date->format('Y-m-d'));
    }

    /** 詳細画面：出勤・退勤時間が一致している */
    public function test_detail_page_displays_correct_start_end_time()
    {
        $attendance = Attendance::first();
        $response = $this->actingAs($this->user)->get("/attendancedetail/{$attendance->id}");

        $response->assertSee('09:00');
        $response->assertSee('18:00');
    }

    /** 詳細画面：休憩時間が表示されている */
    public function test_detail_page_displays_break_time()
    {
        $attendance = Attendance::first();
        $response = $this->actingAs($this->user)->get("/attendancedetail/{$attendance->id}");

        $response->assertSee('1時間0分');
    }
}
