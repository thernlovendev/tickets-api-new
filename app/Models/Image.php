<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use HasFactory, SoftDeletes;

	const TABLE = 'images';

	protected $table = Image::TABLE;
	
	const STATUS = [
		'AVAILABLE' => 'AVAILABLE',
		'OCCUPIED' => 'OCCUPIED',
	];

	protected $fillable = [
		'path',
		'url',
		'imageable_id',
		'imageable_type',
		'type',
		'priority',
		'priority_type'
	];

	protected $appends = 
	[
		'url',
		'status',
	];

    protected $hidden = [
        'path',
		'imageable_id',
		'imageable_type',
    ];

	public function getUrlAttribute($value)
	{
		// $filename = $this->path;
		// if ($filename != null) {
		// 	return Storage::disk('s3')->exists($filename) ?
		// 	Storage::disk('s3')->url($filename) : null;
		// }
		$filename = $this->path;
		if ($filename != null) {
			return Storage::disk('public')->url($filename);
		}
	}

	public function getStatusAttribute($value)
	{
		$is_available = $this->imageable_id === null ? true : false;
		if ($is_available) {
			return Image::STATUS['AVAILABLE'];
		}
		else {
			return Image::STATUS['OCCUPIED'];
		}
	}


    public function imageable()
    {
        return $this->morphTo();
    }
}
