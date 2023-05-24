<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ticket;
use App\Models\StockUsed;

class TicketStock extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'ticket_stocks';

    const RANGE_AGE = [
        'ADULT' => 'Adult',
        'CHILD' => 'Child',
        'NA' => 'N/A',
    ];

    const TYPE = [
        'QR' => 'QR',
        'BAR' => 'Bar',
        'NA' => 'N/A',
    ];

    const STATUS = [
        'USED' => 'Used',
        'VALID' => 'Valid',
    ];

    protected $fillable = [
        'code_number',
        'type',
        'expiration_date',
        'status',
        'range_age_type',
        'ticket_id'
    ];
    
    public function ticket()
    {
        return $this->belongsTo(ticket::class);
    }

    public function stocksUsed()
    {
        return $this->hasMany(StockUsed::class);
    }
}
