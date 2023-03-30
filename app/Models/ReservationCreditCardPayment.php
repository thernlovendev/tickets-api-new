<?php

namespace App\Models;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationCreditCardPayment extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'reservation_credit_card_payments';

    protected $fillable = [
        'reservation_id',
        'total',
        'card_type',
        'payment_status'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

}
