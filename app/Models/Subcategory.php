<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\PriceList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subcategories';

    protected $fillable = [
        'category_id',
        'name',
        'allow_premium_prices'
    ];

    protected $casts = [
        'allow_premium_prices' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'subcategory_ticket');
    }

    public function pricesLists()
    {
        return $this->hasMany(PriceList::class);
    }

}
