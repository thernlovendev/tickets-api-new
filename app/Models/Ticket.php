<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ReservationSubItem;
use App\Models\TicketPrice;
use App\Models\TicketSchedule;
use App\Models\TicketContent;
use App\Models\TicketStock;
use App\Models\Image;
use App\Models\StockCorrectionBalance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tickets';

    const STATUS = [
        'IN_STOCK' => 'In Stock',
        'OUT_OF_STOCK' => 'Out Of Stock'
    ];

    const TYPE = [
        'REGULAR' => 'Regular',
        'BAR_QR' => 'Bar/QR',
        'GUIDE_TOUR' => 'Guide Tour',
        'HARD_COPY' => 'Hard Copy',
        'MUSICAL_SHOW' => 'Musical & Shows',
        'SIM_CARD' => 'SIM Card'
    ];

    const ADDITIONAL_PRICE_TYPE = [
        'NONE' => 'None',
        'PREMIUM' => 'Premium',
        'PREMIUM_S' => 'Premium S'
    ];

    const TYPE_IMAGES = [
        'WIDE' => 'Wide',
        'CARD_IMAGE' => 'Card_image',
        'GALLERY' => 'Gallery'
    ];

    protected $fillable = [
        'company_id',
        'city_id',
        'title_en',
        'title_kr',
        'ticket_template',
        'ticket_type',
        'status',
        'out_of_stock_alert_adult',
        'out_of_stock_alert_child',
        'currency',
        'product_code',
        'additional_price_type',
        'additional_price_amount',
        'additional_price_image',
        'show_in_schedule_page',
        'announcement'
    ];

    protected $casts = [
        'show_in_schedule_page' => 'boolean'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_ticket');
    }

    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'subcategory_ticket');
    }

    public function ticketPrices()
    {
        return $this->hasMany(TicketPrice::class);
    }

    public function ticketContent()
    {
        return $this->hasOne(TicketContent::class);
    }

    public function ticketSchedules()
    {
        return $this->hasMany(TicketSchedule::class);
    }

    public function wideImages()
    {
        return $this->morphMany(Image::class, 'imageable')->where('priority_type', 'wide');
    }

    public function galleryImages()
    {
        return $this->morphMany(Image::class, 'imageable')->where('priority_type', 'gallery');
    }

    public function cardImage()
    {
        return $this->morphOne(Image::class, 'imageable')->where('priority_type', 'card_image');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function ticketStocks()
    {
        return $this->hasMany(TicketStock::class);
    }

    public function stockCorrectionBalance()
    {
        return $this->hasMany(StockCorrectionBalance::class);
    }

    public function reservationSubItems()
    {
        return $this->hasMany(ReservationSubItem::class);
    }
}
