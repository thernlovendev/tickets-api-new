<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\TicketSchedule;
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
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {
                    $days = TicketSchedule::DAYS;
                    return [
                        'company_id' => ['required','exists:companies,id'],
                        'city_id' => ['required','exists:cities,id'],
                        'title_en' => ['required','unique:tickets,title_en'],
                        'title_kr' => ['required','unique:tickets,title_kr'],
                        'ticket_template' => ['required'],
                        'ticket_type' => ['required'],
                        'status' => ['required'],
                        'out_of_stock_alert' => ['required'],
                        'currency' => ['required'],
                        'announcement' => ['required'],
                        'additional_price_type' => ['required'],
                        'additional_price_amount' => ['nullable'],
                        'additional_price_image' => ['nullable'],
                        'show_in_schedule_page' => ['required','boolean'],
                        'card_image' => ['required','exists:images,id'],
                        'card_image.id' => ['required'],
                        'card_image.priority_type' => ['required'],
                        'wide_images' => ['required'],
                        'wide_images.*.id' => ['required','exists:images,id'],
                        'wide_images.*.priority' => ['required'],
                        'wide_images.*.priority_type' => ['required'],
                        'gallery_images' => ['required'],
                        'gallery_images.*.id' => ['required','exists:images,id'],
                        'gallery_images.*.priority' => ['required'],
                        'gallery_images.*.priority_type' => ['required'],
                        'tickets_prices' => ['required'],
                        'tickets_prices.*.type' => ['required'],
                        'tickets_prices.*.age_limit' => ['nullable'],
                        'tickets_prices.*.window_price' => ['nullable'],
                        'tickets_prices.*.sale_price' => ['required'],
                        'tickets_content' => ['nullable'],
                        'tickets_content.*.name' => ['required','distinct'],
                        'tickets_content.*.content' => ['required'],
                        'tickets_schedule' => ['required_if:show_in_schedule_page,true'],
                        'tickets_schedule.*.date_start' => 'date|required_if:show_in_schedule_page,true',
                        'tickets_schedule.*.date_end' => 'date|required_if:show_in_schedule_page,true',
                        'tickets_schedule.*.max_people' => 'integer|required_if:show_in_schedule_page,true',
                        'tickets_schedule.*.week_days' => ['array','required_if:show_in_schedule_page,true', Rule::in($days)],
                    ];
                } break;

            case 'PUT':{
                return [];
            } break;

            case 'DELETE': break;
            default:
            {
                return [];
            } break;
        }
    }
}
