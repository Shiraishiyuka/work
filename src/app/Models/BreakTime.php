<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'adjust_id',
        'start_time',
        'end_time',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function adjust()
    {
        return $this->belongsTo(Adjust::class);
    }
}
