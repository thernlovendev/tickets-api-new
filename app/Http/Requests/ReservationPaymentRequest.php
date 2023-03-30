<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Reservation;
use Illuminate\Validation\ValidationException;

class ReservationPaymentRequest extends FormRequest
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
                    $reservation = $this->route('reservation');
                    
                    if($reservation->status !== Reservation::STATUS['NO_PAID']){
                        throw ValidationException::withMessages([
                            'errors' => ['A payment has already been made, reservation status: '.$reservation->status]
                        ]);
                    }
                    
                    $type = Reservation::PAYMENT_TYPE;
                    return [
                        'payment_type' => ['required',Rule::in($type)],
                        'credit' => ['required_if:payment_type,Cash']
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
