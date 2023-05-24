<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Reservation;
use App\Models\ReservationSubItem;
use App\Models\TicketStock;

class StockUsed extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_used';

    protected $fillable = [
        'date_used',
        'reservation_id',
        'ticket_stock_id',
        'reservation_sub_item_id'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function reservationSubItem()
    {
        return $this->belongsTo(ReservationSubItem::class, 'reservation_sub_item_id');
    }
    
    public function ticketStock()
    {
        return $this->belongsTo(TicketStock::class, 'ticket_stock_id');
    }
}
