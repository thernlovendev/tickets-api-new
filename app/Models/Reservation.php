<?php

namespace App\Models;

use App\Models\VendorComission;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;
use App\Models\ReservationCashPayment;
use App\Models\ReservationCreditCardPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'reservations';

    const PAYMENT_TYPE = [
        'CREDIT_CARD' => 'Credit Card',
        'CASH' => 'Cash'
    ];

    const STATUS = [
        'NO_PAID' => 'No Paid',
        'PAID' => 'Paid',
        'REFUNDED' => 'Refunded',
        'REFUNDED_IN_PROCESS' => 'Refunded in Process'
    ];

    const TICKET_SENT_STATUS = [
        'TO_DO' => 'To Do',
        'SENT' => 'Sent',
        'TBD' => 'TBD'
    ];

    protected $fillable = [
        'departure_date',
        'order_date',
        'order_number',
        'customer_name_en',
        'customer_name_kr',
        'phone',
        'email',
        'discount_amount',
        'subtotal',
        'total',
        'memo',
        'history',
        'payment_type',
        'ticket_sent_status',
        'status',
        'created_by'
    ];

    public function reservationItems()
    {
        return $this->hasMany(ReservationItem::class);
    }

    public function reservationCashPayments()
    {
        return $this->hasMany(ReservationCashPayment::class);
    }

    public function reservationCreditCardPayments()
    {
        return $this->hasMany(ReservationCreditCardPayment::class);
    }

    public function vendorComissions()
    {
        return $this->hasMany(VendorComission::class);
    }

}
