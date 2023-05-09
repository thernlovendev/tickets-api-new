<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockTicketRequest extends FormRequest
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
        $range_age = StockTicket::RANGE_AGE;
        $type = StockTicket::TYPE;
        $status = StockTicket::STATUS;
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {
                    return [
                        'file_import' => ['required','mimes:csv,xlsx,xls'],
                        'expiration_date' =>['required','date','after:today'],
                        'type' =>['required', Rule::in($type)],
                        'status' =>['required',Rule::in($range_age)],
                        'range_age_type' =>['required',Rule::in($range_age)],
                        'ticket_id' =>['required']
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
