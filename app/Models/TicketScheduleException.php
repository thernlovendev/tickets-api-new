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

    protected $appends = [
        'total_quantity_sold',
        'show_on_calendar_exception'
    ];

    protected $casts = [
        'show_on_calendar' => 'boolean',
    ];

    public function ticketSchedule()
    {
        return $this->belongsTo(TicketSchedule::class, 'ticket_schedule_id');
    }

    public function getTotalQuantitySoldAttribute()
    {
        $ticket_id = $this->ticketSchedule ? $this->ticketSchedule->ticket_id : null;
        $datetime = $this->date.' '.$this->time;

        if($ticket_id){
            
            $quantity_sold = ReservationItem::whereHas('reservationSubItems', function($query) use ($ticket_id, $datetime){
                    $query->where('ticket_id', $ticket_id)->where('rq_schedule_datetime', $datetime);
                })->sum('quantity');

            return $this->attributes['total_quantity_sold'] = $quantity_sold;

        } 

        return $this->attributes['total_quantity_sold'] = 0;
    }

    public function getShowOnCalendarExceptionAttribute($value)
    {
        if($this->total_quantity_sold >= $this->max_people){
            return false;
        }
        return $this->show_on_calendar;
    }

}
