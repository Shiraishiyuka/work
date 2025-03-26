<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Adjust;
use Carbon\Carbon;

class AdjustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 既存の User を取得
        $user = User::first(); // 一番最初のユーザーを取得（例）
        $attendance = Attendance::first(); // 一番最初の勤怠データを取得

        // `User` や `Attendance` が存在しない場合は作成
        if (!$user) {
            $user = User::factory()->create();
        }
        if (!$attendance) {
            $attendance = Attendance::factory()->create(['user_id' => $user->id]);
        }

        // 修正データを作成
        Adjust::create([
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,  // ✅ 存在する user_id を使用
            'date' => now()->subDays(3)->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '17:00:00',
            'break_minutes' => 60,
            'remarks' => '時間修正',
            'status' => 'pending',
        ]);
    }
}
