<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NavigationSubMenu extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'navigation_sub_menus';

    protected $fillable = [
        'navigation_menu_id',
        'template_id',
        'url',
        'name',
        'ticket_id'
    ];
    
    public function navigationMenu()
    {
        return $this->belongsTo(NavigationMenu::class, 'navigation_menu_id');
    }
}
