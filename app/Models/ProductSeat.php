<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSeat extends Model
{
    use HasFactory;

    protected $table = 'product_seats';

    protected $appends = ['product_date_time'];

    protected $primaryKey = 'product_id';
    public $incrementing = false;

    public $timestamps = false; // Desactivar marcas de tiempo

    protected $fillable = [
        'product_id',
        'price',
        'regular_price',
        'currency',
        'bestseats',
        'availability',
        'base_price',
        'facility_fee',
        'supplier_fee',
    ];

    public function getProductDateTimeAttribute()
    {
        $dateString = $this->product_date;
        $timeString = $this->product_time;

        // Step 1: Convert date and time strings to a timestamp
        $timestamp = strtotime("$dateString $timeString");

        // Step 2: Format the timestamp in the desired format
        $formattedDateTime = date("M j, Y, g:i a", $timestamp);
        
        return $formattedDateTime;
    }

    // Desactivar la actualización de 'updated_at' al guardar el modelo
    public function touches($relation)
    {
        return [];
    }
}
