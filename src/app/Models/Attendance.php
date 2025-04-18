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

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function calculateBreakMinutes()
{
    if (!$this->break_start_time || !$this->break_end_time) {
        return 0;
    }

    $start = Carbon::parse($this->break_start_time);
    $end = Carbon::parse($this->break_end_time);

    if ($end < $start) {
        $end->addDay();
    }

    return $start->diffInMinutes($end);
}


    public function calculateWorkMinutes()
{
    if (!$this->start_time || !$this->end_time) {
        return 0;
    }

    $start = Carbon::parse($this->start_time);
    $end = Carbon::parse($this->end_time);

    if ($end < $start) {
        $end->addDay();
    }

    return $start->diffInMinutes($end) - $this->calculateBreakMinutes();
}

public function breakTimes()
{
    return $this->hasMany(BreakTime::class);
}
    
public function getCalculatedBreakMinutesAttribute()
{
    return $this->breakTimes
        ->filter(fn($bt) => $bt->start_time && $bt->end_time)
        ->reduce(function ($carry, $bt) {
            $start = \Carbon\Carbon::createFromFormat('H:i:s', $bt->start_time);
            $end = \Carbon\Carbon::createFromFormat('H:i:s', $bt->end_time);

            if ($end < $start) {
                $end->addDay();
            }

            return $carry + $start->diffInMinutes($end);
        }, 0);
}



}
