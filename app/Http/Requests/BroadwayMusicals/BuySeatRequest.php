<?php

namespace App\Http\Requests\BroadwayMusicals;

use Illuminate\Foundation\Http\FormRequest;

class BuySeatRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $salesTypes = "F,G";
        return [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'show_code' => 'required|string',
            'event_date_time' => 'string',
            'price' => 'required',
            'booking_last_name' => 'required|string',
            'booking_first_name' => 'string',
            'booking_reference_number' => 'string',
            'booking_notes' => 'string',
            'area' => 'string',
            'low_seat_num' => 'integer|required',
            'high_seat_num' => 'integer|required',
            'row' => 'string|required',
            'session_id' => 'string|required',
            'booking_email_address' => 'required|email',
            'country_code' => 'integer',
            'area_code' => 'integer',
        ];
    }
}
