<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ticket;
use App\Models\StockUsed;
use App\Models\TicketStockPdf;

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
        'TEXT' => 'Text',
        'ZIP' => 'Zip',
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

    public function pdf()
    {
        return $this->hasOne(TicketStockPdf::class);
    }
}
