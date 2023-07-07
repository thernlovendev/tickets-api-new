<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderLookupRequest extends FormRequest
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
    {        switch($this->method()) {
            case 'GET':
                {
                    return [
                        'email' => ['required','email','exists:reservations,email'],
                        'order_number' => ['required','exists:reservations,order_number','min:7'],
                    ];
                } break;

            case 'POST':
                {
                    return [];
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

    public function messages()
    {
        return [
            'email.exists:reservations,email' => 'There is no order associated with this email',
            'order_number.exists:reservations,order_number' => 'The order you wrote is not found',
        ];
    }
}
