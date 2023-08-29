<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $table = 'configurations';

    const PAYMENT_TYPE = [
        'STRIPE' => 'STRIPE',
        'SQUARE' => 'SQUARE'
    ];

    protected $fillable = [
        'key',
        'value'
    ];
}
