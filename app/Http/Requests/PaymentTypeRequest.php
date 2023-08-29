<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Configuration;

class PaymentTypeRequest extends FormRequest
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
        $type = Configuration::PAYMENT_TYPE;
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {
                    return [];
                } break;

            case 'PUT':{
                return [
                    'value' => ['required', Rule::in($type)],
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
