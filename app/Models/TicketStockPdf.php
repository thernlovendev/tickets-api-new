<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TicketStockPdf extends Model
{
    use HasFactory;

    const TABLE = 'ticket_stocks_pdf';

	protected $table = TicketStockPdf::TABLE;
	
	protected $fillable = [
		'path',
		'name',
        'ticket_stock_id'
	];

	protected $appends = 
	[
		'url',
	];

    protected $hidden = [
        'path',
    ];

	public function getUrlAttribute($value)
	{
		$filename = $this->path;
		if ($filename != null) {
			return Storage::disk('public')->url($filename);
		}
	}


}
