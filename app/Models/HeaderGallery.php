<?php

namespace App\Models;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeaderGallery extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'header_galleries';

    protected $fillable = [
        'title',
        'first_phrase',
        'second_phrase',
        'is_show',
    ];

    const STATUS = [
        'SHOW' => 1,
        'HIDE' => 0
    ];

    const TYPE_IMAGES = [
        'GALLERY' => 'gallery',
        'MAIN_IMAGE' => 'main_image',
    ];

    public function galleryImages()
    {
        return $this->morphMany(Image::class, 'imageable')->where('priority_type', 'gallery');
    }

    public function mainImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('priority_type', 'main_image');
    }

    public function templates()
    {
        return $this->hasMany(ReservationSubItem::class);
    }
}
