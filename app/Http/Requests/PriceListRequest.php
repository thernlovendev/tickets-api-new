<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\TicketSchedule;
class PriceListRequest extends FormRequest
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
                    return [
                        'category_id' => ['required','exists:categories,id'],
                        'prices' => ['required'],
                        'prices.*.product_type' => 'required|distinct',
                        'prices.*.quantity' => 'required|numeric',
                        'prices.*.child_price' => 'required_if:adult_price,true|numeric',
                        'prices.*.adult_price' => 'required_if:child_price,true|numeric',
                        'prices.*.subcategory_id' => ['required','exists:subcategories,id'],
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
