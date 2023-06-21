<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockCorrectionBalance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'stock_correction_balance';

    protected $fillable = [
        'register_date',
        'stock_in',
        'stock_out',
        'type',
        'range_age_type',
        'ticket_id',
        'created_by'
    ];
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
