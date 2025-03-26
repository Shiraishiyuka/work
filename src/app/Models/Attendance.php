<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'date',
    'start_time',
    'end_time',
    'break_start_time', 
    'break_end_time', 
    'break_minutes',
    'work_minutes',
    'remarks',
    'status',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔹 休憩時間を計算（複数回対応）
    public function calculateBreakMinutes()
{
    if (!$this->break_start_time || !$this->break_end_time) {
        return 0; // 休憩が記録されていない場合は 0 分
    }

    $start = Carbon::parse($this->break_start_time);
    $end = Carbon::parse($this->break_end_time);

    if ($end < $start) {
        $end->addDay();
    }

    return $start->diffInMinutes($end);
}

    // 🔹 実働時間を計算（日付超え対応）
    public function calculateWorkMinutes()
{
    if (!$this->start_time || !$this->end_time) {
        return 0;  // ✅ 出勤 or 退勤時間がない場合、勤務時間を 0 にする
    }

    $start = Carbon::parse($this->start_time);
    $end = Carbon::parse($this->end_time);

    if ($end < $start) {
        $end->addDay();
    }

    return $start->diffInMinutes($end) - $this->calculateBreakMinutes();
}
    
}
