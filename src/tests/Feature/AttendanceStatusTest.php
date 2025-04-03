<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
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
        // ユーザー作成とログイン
        $user = User::factory()->create();

        // 現在の時刻を取得（テスト時点の基準）
        $now = Carbon::now();

        // ログイン状態で出勤画面を表示
        $response = $this->actingAs($user)->get('/attendance');

        // 表示されている日付を確認（例：2025年03月27日（木））
        $expectedDate = $now->translatedFormat('Y年m月d日（D）');
        $response->assertSee($expectedDate);

        // 表示されている時刻を確認（例：12:34）
        $expectedTime = $now->format('H:i');
        $response->assertSee($expectedTime);
    }

     protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(); // CSRFチェックを無効化
    }

    /** 勤務外ステータスが表示されるか確認 */
    public function test_status_displays_not_working()
    {
        $user = User::factory()->create();

        // 勤務外としてセッションにセットしてアクセス
        $response = $this->actingAs($user)
                         ->withSession(['attendance_status' => 'not_working'])
                         ->get('/attendance');

        $response->assertSee('勤務外');
    }

    /** 勤務中ステータスが表示されるか確認 */
    public function test_status_displays_working()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->withSession(['attendance_status' => 'working'])
                         ->get('/attendance');

        $response->assertSee('出勤中');
    }

    /** 休憩中ステータスが表示されるか確認 */
    public function test_status_displays_on_break()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->withSession(['attendance_status' => 'on_break'])
                         ->get('/attendance');

        $response->assertSee('休憩中');
    }

    /** 退勤済ステータスが表示されるか確認 */
    public function test_status_displays_finished()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                         ->withSession(['attendance_status' => 'finished'])
                         ->get('/attendance');

        $response->assertSee('退勤済');
    }
}
