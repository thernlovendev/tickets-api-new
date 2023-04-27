<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketScheduleException extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ticket_schedule_exceptions';

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
        'ticket_schedule_id',
        'date',
        'max_people',
        'time',
        'day',
        'show_on_calendar'
    ];

    public function ticketSchedule()
    {
        return $this->belongsTo(TicketSchedule::class, 'ticket_schedule_id');
    }
}
