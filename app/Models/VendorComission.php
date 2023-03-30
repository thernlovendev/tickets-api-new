<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorComission extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'vendor_comissions';

    protected $fillable = [
        'reservation_id',
        'user_id',
        'type',
        'comission_amount'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

}
