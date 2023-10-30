<?php

namespace App\Http\Requests\BroadwayMusicals;

use Illuminate\Foundation\Http\FormRequest;

class SelectSeatRequest extends FormRequest
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
            'sales_type' => 'required|in:' . $salesTypes,
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'show_code' => 'required|string',
            'event_date_time' => 'required|string',
        ];
    }
}
