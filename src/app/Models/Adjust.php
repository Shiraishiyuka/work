<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjust extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'user_id',
        'date',          // 修正後の日付
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

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    protected $casts = [
        'date' => 'date', // ✅ これで `Carbon` インスタンスとして扱える
        'created_at' => 'datetime',
    ];
}
