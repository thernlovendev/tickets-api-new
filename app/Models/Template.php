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
        'created_by',
        'subject'
    ];

    const STATUS = [
        'PUBLISH' => 'Publish',
        'UNPUBLISH' => 'Unpublish'
    ];

    const TYPE = [
        'EMAIL' => 'Email',
        'WEB_PAGE' => 'Web Page',
        'IMAGE' => 'Image'
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

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable')->where('priority_type', 'template');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function headerGallery()
    {
        return $this->belongsTo(HeaderGallery::class);
    }
}
