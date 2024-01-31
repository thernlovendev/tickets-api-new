<?php

namespace App\Models;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketPrice extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_PRICE = [
        'ADULT' => 'Adult',
        'CHILD' => 'Child',
        'NA' => 'N/A',
    ];
    
    protected $table = 'ticket_prices';

    protected $fillable = [
        'ticket_id',
        'type',
        'age_limit',
        'window_price',
        'sale_price'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

}
