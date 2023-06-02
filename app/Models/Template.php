<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'templates';

    protected $fillable = [
        'title',
        'type',
        'header_gallery_id',
        'content',
        'status',
        'created_by'
    ];

    const STATUS = [
        'PUBLISH' => 'Publish',
        'UNPUBLISH' => 'Unpublish'
    ];

    public function navigationSubMenus()
    {
        return $this->hasMany(NavigationSubMenu::class);
    }

    public function headerImage()
    {
        return $this->morphOne(Image::class, 'imageable');
        // ->where('priority_type', 'card_image');
    }
}
