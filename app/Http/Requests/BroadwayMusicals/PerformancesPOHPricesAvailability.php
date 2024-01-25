<?php

namespace App\Http\Requests\BroadwayMusicals;

use Illuminate\Foundation\Http\FormRequest;

class PerformancesPOHPricesAvailability extends FormRequest
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
            'sales_type' => 'required|in:'.$salesTypes,
            'show_code' => 'required|string',
            'show_city_code' => 'required|string',
            'event_date_begin' => ['required','string','date','after:today'],
            'event_date_end' => ['required','string','date','after:'.$this->event_date_begin],
            'availability_type'=>'nullable|in:'.$salesTypes,
            'best_seats_only' => 'nullable|boolean',
            'last_change_date' => ['nullable','string','date'],
        ];
    }
}
