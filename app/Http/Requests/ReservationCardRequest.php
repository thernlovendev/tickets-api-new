<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationCardRequest extends FormRequest
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
                        'full_name' => ['required'],
                        'first_name' => ['required'],
                        'last_name' => ['required'],
                        'email' => ['required','email','confirmed'],
                        'email_confirmation' => ['required','email','max:255'],
                        'departure_date' => ['nullable','date'],
                        'phone' => ['required'],
                        'card' => ['required','array'],
                        'card.number' => ['required'],
                        'card.exp_month' => ['required'],
                        'card.exp_year' => ['required'],
                        'card.cvc' => ['required']
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
