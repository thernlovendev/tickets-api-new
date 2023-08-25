<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\TicketStock;

class StockTicketZipRequest extends FormRequest
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
        $range_age = TicketStock::RANGE_AGE;
        $type = TicketStock::TYPE;
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {
                    return [
                        'file_import' => ['required','mimes:zip'],
                        'expiration_date' =>['required','date','after:today'],
                        'type' =>['required', Rule::in($type)],
                        'range_age_type' =>['required',Rule::in($range_age)],
                        'ticket_id' =>['required', 'exists:tickets,id']
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
