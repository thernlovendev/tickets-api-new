<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NavigationMenu extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'navigation_menus';

    protected $fillable = [
        'name',
        'url',
        'template',
        'static_page'
    ];
    
    public function navigationSubMenus()
    {
        return $this->hasMany(NavigationSubMenu::class);
    }
}
