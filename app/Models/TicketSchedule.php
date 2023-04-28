<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ticket_schedules';

    const DAYS = [
        'MONDAY' => 'Monday',
        'TUESDAY' => 'Tuesday',
        'WEDNESDAY' => 'Wednesday',
        'THURSDAY' => 'Thursday',
        'FRIDAY' => 'Friday',
        'SATURDAY' => 'Saturday',
        'SUNDAY' => 'Sunday'
    ];

    protected $fillable = [
        'ticket_id',
        'date_start',
        'date_end',
        'max_people',
        'week_days',
        'time'
    ];

    protected $casts = [
        'week_days' => 'array',
    ];
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function ticketScheduleExceptions()
    {
        return $this->hasMany(TicketScheduleException::class);
    }
}
