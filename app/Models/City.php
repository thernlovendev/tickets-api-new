<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'cities';

    protected $fillable = [
        'company_id',
        'name',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class, 'city_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
