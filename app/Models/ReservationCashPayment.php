<?php

namespace App\Models;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationCashPayment extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'reservation_cash_payments';

    protected $fillable = [
        'reservation_id',
        'debit',
        'credit',
        'total'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

}
