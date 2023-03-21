<?php

namespace App\Models;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'companies';

    protected $fillable = [
        'name',
    ];
    
    public function cities()
    {
        return $this->hasMany(City::class, 'city_id');
    }
}
