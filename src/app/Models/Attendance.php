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
        'break_minutes',
        'work_minutes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateWorkMinutes()
    {
        if ($this->start_time && $this->end_time) {
            $start = Carbon::createFromFormat('H:i:s', $this->start_time);
            $end = Carbon::createFromFormat('H:i:s', $this->end_time);
            return $start->diffInMinutes($end) - $this->break_minutes;
        }
        return 0;
    }


    
}
