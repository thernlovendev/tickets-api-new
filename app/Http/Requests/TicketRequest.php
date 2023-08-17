<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Template;
use App\Models\Ticket;
use App\Models\TicketSchedule;
use App\Models\TicketPrice;
class TicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $days = TicketSchedule::DAYS;
        $ticket_type = Ticket::TYPE['GUIDE_TOUR'];
        $status = Ticket::STATUS;
        $type_images = Ticket::TYPE_IMAGES;
        $additional_type = Ticket::ADDITIONAL_PRICE_TYPE;
        $price_types = TicketPrice::TYPE_PRICE;
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {
                    return [
                        'company_id' => ['required','exists:companies,id'],
                        'city_id' => ['required','exists:cities,id'],
                        'title_en' => ['required','unique:tickets,title_en'],
                        'title_kr' => ['required','unique:tickets,title_kr'],
                        'template_id' => ['required',Rule::exists('templates', 'id')->where(function ($query) {
                            $query->where('type', 'Image');
                        }),'integer'],
                        'ticket_type' => ['required'],
                        'status' => ['required', Rule::in($status)],
                        'out_of_stock_alert_adult' => ['nullable', 'integer'],
                        'out_of_stock_alert_child' => ['nullable', 'integer'],
                        'currency' => ['required'],
                        'announcement' => ['nullable','max:100'],
                        'additional_price_type' => ['required',Rule::in($additional_type)],
                        'additional_price_amount' => ['nullable'],
                        'additional_price_image' => ['nullable'],
                        'premium_amount'=> ['numeric','min:0'],
                        'premium_s_amount' => ['numeric','min:0'],
                        'show_in_schedule_page' => ['required','boolean'],
                        'card_image' => ['required'],
                        'card_image.id' => ['required','exists:images,id'],
                        'card_image.priority_type' => ['required',Rule::in($type_images)],
                        'wide_images' => ['required'],
                        'wide_images.*.id' => ['required','exists:images,id'],
                        'wide_images.*.priority' => ['required'],
                        'wide_images.*.priority_type' => ['required',Rule::in($type_images)],
                        'gallery_images' => ['required'],
                        'gallery_images.*.id' => ['required','exists:images,id'],
                        'gallery_images.*.priority' => ['required'],
                        'gallery_images.*.priority_type' => ['required',Rule::in($type_images)],
                        'tickets_categories' => ['required'],
                        'tickets_categories.*.category_id' => ['required','exists:categories,id'],
                        'tickets_subcategories' => ['required'],
                        'tickets_subcategories.*.subcategory_id' => ['required','exists:subcategories,id'],
                        'tickets_prices' => ['required'],
                        'tickets_prices.*.type' => ['required',Rule::in($price_types)],
                        'tickets_prices.*.age_limit' => ['nullable'],
                        'tickets_prices.*.window_price' => ['nullable'],
                        'tickets_prices.*.sale_price' => ['required'],
                        'ticket_content.content' => ['required'],
                        'tickets_schedule' => ['required_if:ticket_type,'.$ticket_type],
                        'tickets_schedule.*.date_start' => 'date|required_if:ticket_type,'.$ticket_type,
                        'tickets_schedule.*.date_end' => 'date|required_if:ticket_type,'.$ticket_type,
                        'tickets_schedule.*.max_people' => 'integer|required_if:ticket_type,'.$ticket_type,
                        'tickets_schedule.*.week_days' => ['array','required_if:ticket_type,'.$ticket_type, Rule::in($days)],
                        'tickets_schedule.*.time' => ['required_if:ticket_type,'.$ticket_type,'date_format:H:i'],
                        'tickets_schedule.*.ticket_schedule_exceptions' => ['nullable'],
                        'tickets_schedule.*.ticket_schedule_exceptions.*.date' => ['required','date','date_format:Y-m-d'],
                        'tickets_schedule.*.ticket_schedule_exceptions.*.max_people' => ['required','integer','min:0'],
                        'tickets_schedule.*.ticket_schedule_exceptions.*.show_on_calendar' => ['required','boolean']
                    ];
                } break;

            case 'PUT':{
                $ticket = $this->route('ticket');
                return [
                        'title_en' => ['required',Rule::unique('tickets')->ignore($ticket->id)],
                        'title_kr' => ['required',Rule::unique('tickets')->ignore($ticket->id)],
                        'template_id' => ['required',Rule::exists('templates', 'id')->where(function ($query) {
                            $query->where('type', 'Image');
                        }),'integer'],
                        'ticket_type' => ['required'],
                        'status' => ['required', Rule::in($status)],
                        'out_of_stock_alert_adult' => ['nullable', 'integer'],
                        'out_of_stock_alert_child' => ['nullable', 'integer'],
                        'currency' => ['required'],
                        'announcement' => ['nullable','max:200'],
                        'additional_price_type' => ['required',Rule::in($additional_type)],
                        'additional_price_amount' => ['nullable'],
                        'premium_amount'=> ['numeric','min:0'],
                        'premium_s_amount' => ['numeric','min:0'],
                        'additional_price_image' => ['nullable'],
                        'show_in_schedule_page' => ['required','boolean'],
                        'card_image' => ['required'],
                        'card_image.id' => ['required','exists:images,id'],
                        'card_image.priority_type' => ['required',Rule::in($type_images)],
                        'wide_images' => ['required'],
                        'wide_images.*.id' => ['required','exists:images,id'],
                        'wide_images.*.priority' => ['required'],
                        'wide_images.*.priority_type' => ['required',Rule::in($type_images)],
                        'gallery_images' => ['required'],
                        'gallery_images.*.id' => ['required','exists:images,id'],
                        'gallery_images.*.priority' => ['required'],
                        'gallery_images.*.priority_type' => ['required',Rule::in($type_images)],
                        'tickets_categories' => ['required'],
                        'tickets_categories.*.category_id' => ['required','exists:categories,id'],
                        'tickets_subcategories' => ['required'],
                        'tickets_subcategories.*.subcategory_id' => ['required','exists:subcategories,id'],
                        'tickets_prices' => ['required'],
                        'tickets_prices.*.id' => ['nullable'],
                        'tickets_prices.*.type' => ['required',Rule::in($price_types)],
                        'tickets_prices.*.age_limit' => ['nullable'],
                        'tickets_prices.*.window_price' => ['nullable'],
                        'tickets_prices.*.sale_price' => ['required'],
                        'ticket_content.content' => ['required'],
                        'tickets_schedule' => ['required_if:ticket_type,'.$ticket_type],
                        'tickets_schedule.*.date_start' => 'date|required_if:ticket_type,'.$ticket_type,
                        'tickets_schedule.*.date_end' => 'date|required_if:ticket_type,'.$ticket_type,
                        'tickets_schedule.*.max_people' => 'integer|required_if:ticket_type,'.$ticket_type,
                        'tickets_schedule.*.time' => ['required_if:ticket_type,'.$ticket_type,'date_format:H:i'],
                        'tickets_schedule.*.week_days' => ['array','required_if:ticket_type,'.$ticket_type, Rule::in($days)],
                ];
            } break;

            case 'DELETE': break;
            default:
            {
                return [];
            } break;
        }
    }
}
