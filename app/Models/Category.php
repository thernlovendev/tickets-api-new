<?php

namespace App\Models;

use App\Models\Subcategory;
use App\Models\City;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'categories';

    protected $fillable = [
        'city_id',
        'name',
    ];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'category_ticket');
    }


}
