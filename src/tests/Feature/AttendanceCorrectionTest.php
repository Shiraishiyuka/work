<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceCorrectionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    protected $user;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();


        $this->user = User::factory()->create();
        $this->admin = User::factory()->create([
            'email' => 'admin@example.com',
            'is_admin' => true
        ]);
    }


    public function test_start_time_after_end_time_validation()
    {
        $attendance = Attendance::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->from("/adjustments/{$attendance->id}")
            ->post("/adjustments/{$attendance->id}", [
                'start_time' => '18:00',
                'end_time' => '09:00',
                'break_times' => [
                    ['start_time' => '12:00', 'end_time' => '13:00'],
                ],
                'remarks' => '理由'
            ]);

        $response->assertSessionHasErrors(['start_time']);
        $this->assertStringContainsString(
            '出勤時間もしくは退勤時間が不適切な値です',
            session('errors')->first('start_time')
        );
    }


    public function test_break_start_after_end_time_validation()
    {
        $attendance = Attendance::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->from("/adjustments/{$attendance->id}")
            ->post("/adjustments/{$attendance->id}", [
                'start_time' => '09:00',
                'end_time' => '18:00',
                'break_times' => [
                    ['start_time' => '19:00', 'end_time' => '20:00'],
                ],
                'remarks' => '理由'
            ]);

        $response->assertSessionHasErrors(['break_times.0.start_time']);
        $this->assertStringContainsString(
            '休憩時間が勤務時間外です',
            session('errors')->first('break_times.0.start_time')
        );
    }


    public function test_break_end_after_end_time_validation()
    {
        $attendance = Attendance::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->from("/adjustments/{$attendance->id}")
            ->post("/adjustments/{$attendance->id}", [
                'start_time' => '09:00',
                'end_time' => '18:00',
                'break_times' => [
                    ['start_time' => '13:00', 'end_time' => '19:00'],
                ],
                'remarks' => '理由'
            ]);

        $response->assertSessionHasErrors(['break_times.0.end_time']);
        $this->assertStringContainsString(
            '休憩時間が勤務時間外です',
            session('errors')->first('break_times.0.end_time')
        );
    }


    public function test_remarks_required_validation()
    {
        $attendance = Attendance::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->from("/adjustments/{$attendance->id}")
            ->post("/adjustments/{$attendance->id}", [
                'start_time' => '09:00',
                'end_time' => '18:00',
                'break_times' => [
                    ['start_time' => '12:00', 'end_time' => '13:00'],
                ],
                'remarks' => ''
            ]);

        $response->assertSessionHasErrors(['remarks']);
        $this->assertStringContainsString(
            '備考を記入してください',
            session('errors')->first('remarks')
        );
    }


    public function test_approved_adjustment_shows_in_admin_view()
    {
        $attendance = Attendance::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)->post("/adjustments/{$attendance->id}", [
            'start_time' => '09:00',
            'end_time' => '18:00',
            'break_times' => [
                ['start_time' => '12:00', 'end_time' => '13:00'],
            ],
            'remarks' => '理由あり'
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/application_request');
        $response->assertSee('理由あり');
    }


    public function test_user_sees_own_adjustments()
    {
        $attendance = Attendance::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)->post("/adjustments/{$attendance->id}", [
            'start_time' => '09:00',
            'end_time' => '18:00',
            'break_times' => [
                ['start_time' => '12:00', 'end_time' => '13:00'],
            ],
            'remarks' => '理由あり'
        ]);

        $response = $this->actingAs($this->user)->get('/application_request');
        $response->assertSee('理由あり');
    }



    public function test_admin_approved_adjustments_are_visible()
    {
        $attendance = Attendance::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)->post("/adjustments/{$attendance->id}", [
            'start_time' => '09:00',
            'end_time' => '18:00',
            'break_times' => [
                ['start_time' => '12:00', 'end_time' => '13:00'],
            ],
            'remarks' => '承認希望'
        ]);

        $adjust = \App\Models\Adjust::where('attendance_id', $attendance->id)->first();
        $adjust->status = 'approved';
        $adjust->save();

        $response = $this->actingAs($this->admin)->get('/admin/application_request');
        $response->assertSee('承認済み');
    }


    public function test_adjustment_detail_page_shows_correctly()
    {
        $attendance = Attendance::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)->post("/adjustments/{$attendance->id}", [
            'start_time' => '09:00',
            'end_time' => '18:00',
            'break_times' => [
                ['start_time' => '12:00', 'end_time' => '13:00'],
            ],
            'remarks' => '詳細確認'
        ]);

        $response = $this->actingAs($this->user)->get("/application_approval/{$attendance->id}");
        $response->assertSee('詳細確認');
    }
}
