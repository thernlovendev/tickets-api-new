<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Inventory;

class InventoryRequest extends FormRequest
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
        $age = Inventory::AGE_RANGE;
        $type_code = Inventory::TYPE_CODE;
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {
                    return [
                        'ticket_id' => ['required','exists:tickets,id'],
                        'register_date' => ['required', 'date'],
                        'stock_out' =>['required_if:stock_in,==,0','numeric','min:0'],
                        'stock_in' =>['required_if:stock_out,==,0','numeric','min:0'],
                        'type_code'=>['nullable', Rule::in($type_code)],
                        'age_range'=>['required',Rule::in($age)],
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
