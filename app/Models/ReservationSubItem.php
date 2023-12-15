<?php

namespace App\Models;

use App\Models\Subcategory;
use App\Models\Ticket;
use App\Models\TicketStock;
use App\Models\ReservationItem;
use App\Models\OptionSchedule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationSubItem extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'reservation_sub_items';
    
    const SEND_STATUS = [
        'REFUNDED' => 'Refunded',
        'OFFICE_PICKUP' => 'Office Pickup',
        'TO_DO' => 'To-Do',
        'SENT' => 'Sent',
        'TBD' => 'TBD',
        'PICKED_UP' => 'Picked Up'
    ];

    protected $fillable = [
        'rq_schedule_datetime',
        'addition',
        'reservation_item_id',
        'ticket_id',
        'ticket_sent_status',
        'ticket_sent_date',
        'refund_status',
        'refund_sent_date',
        'pdf_path',
        'seating_info',
        'musical_order'
    ];

    public function reservationItem()
    {
        return $this->belongsTo(ReservationItem::class, 'reservation_item_id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function stocksUsed()
    {
        return $this->hasMany(StockUsed::class);
    }

    public function optionsSchedules()
    {
        return $this->hasMany(OptionSchedule::class);
    }
}
