<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceList extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'price_lists';

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'product_type',
        'child_price',
        'adult_price',
        'quantity',
    ];

}
