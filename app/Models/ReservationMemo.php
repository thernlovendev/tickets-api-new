<?php

namespace App\Models;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationMemo extends Model
{
    use HasFactory;
    protected $table = 'memo';

    protected $fillable = [
        'reservation_id',
        'user_id',
        'action',
        'key',
        'description'
    ];
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
