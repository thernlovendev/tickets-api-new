<?php

namespace App\Models;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ticket_contents';

    protected $fillable = [
        'ticket_id',
        'name',
        'content'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

}
