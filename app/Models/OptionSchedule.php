<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ReservationSubItem;

class OptionSchedule extends Model
{
    use HasFactory;

    protected $table = 'options_schedules';

    protected $fillable = [
        'datetime',
        'order',
        'reservation_sub_item_id',
    ];

    public function reservationSubItem()
    {
        return $this->belongsTo(ReservationSubItem::class, 'reservation_sub_item_id');
    }
}
