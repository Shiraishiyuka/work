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
        // すでに存在する `attendances` からランダムに選択
        $attendances = Attendance::all();

        foreach ($attendances as $attendance) {
            // 30% の確率で修正申請を作成（全データに申請を作ると多すぎるため）
            if (rand(1, 100) > 30) {
                continue;
            }

            // 修正前の日付を取得
            $original_date = $attendance->date;

            // 日付をランダムで ±3 日変更（範囲：`original_date - 3日` ～ `original_date + 3日`）
            $date = Carbon::parse($original_date)->addDays(rand(-3, 3))->toDateString();

            // 出勤時間を前後30分ランダムで変更
            $start_time = Carbon::parse($attendance->start_time)->addMinutes(rand(-30, 30))->format('H:i:s');

            // 退勤時間を前後30分ランダムで変更
            $end_time = Carbon::parse($attendance->end_time)->addMinutes(rand(-30, 30))->format('H:i:s');

            // 休憩時間を変更（±15分）
            $break_minutes_options = [0, 15, 30, 45, 60];
            $break_minutes = $attendance->break_minutes + $break_minutes_options[array_rand($break_minutes_options)];

            // 修正理由をランダムに選択
            $remarks_options = ['打刻ミス', '遅刻', '早退', 'システム不具合', 'その他'];
            $remarks = $remarks_options[array_rand($remarks_options)];

            // `adjusts` テーブルにデータを挿入
            Adjust::create([
                'attendance_id' => $attendance->id,
                'user_id' => $attendance->user_id,
                'original_date' => $original_date, // 修正前の日付
                'date' => $date, // 修正後の日付
                'start_time' => $start_time,
                'end_time' => $end_time,
                'break_minutes' => max(0, $break_minutes), // 負数を防ぐ
                'remarks' => $remarks,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
