<?php

namespace App\Models;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'companies';

    const STATUS = [
        'ACTIVE' => 'Active',
        'UNACTIVE' => 'Unactive',
    ];

    protected $fillable = [
        'name',
        'status'
    ];
    
    public function cities()
    {
        return $this->hasMany(City::class, 'city_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'user_id');
    }
}
