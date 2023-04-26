<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    const AGE_RANGE = [
        'ADULT' => 'Adult',
        'CHILD' => 'Child',
        'N/A' => 'N/A',
    ];

    const TYPE_CODE = [
        'QR' => 'QR',
        'BAR' => 'Bar',
    ];

    protected $fillable = [
        'ticket_id',
        'register_date',
        'stock_in',
        'stock_out',
        'type_code',
        'age_range'
    ];

    public function ticket()
    {
        return $this->belongsTo(Company::class, 'ticket_id');
    }
}
