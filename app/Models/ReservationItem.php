<?php

namespace App\Models;

use App\Models\Reservation;
use App\Models\ReservationSubItem;
use App\Models\Ticket;
use App\Models\PriceList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationItem extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_PRICE = [
        'ADULT' => 'Adult',
        'CHILD' => 'Child',
    ];

    
    protected $table = 'reservation_items';

    protected $fillable = [
        'reservation_id',
        'category_id',
        'subcategory_id',
        'price_list_id',
        'adult_child_type',
        'child_age',
        'price',
        'addition',
        'quantity',
        'total',
        'refund_status',
        'refund_sent_date'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function reservationSubItems()
    {
        return $this->hasMany(ReservationSubItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function priceList()
    {
        return $this->belongsTo(PriceList::class, 'price_list_id');
    }

}
