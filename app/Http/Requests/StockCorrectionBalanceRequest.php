<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TicketStock;
use Illuminate\Validation\Rule;

class StockCorrectionBalanceRequest extends FormRequest
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
                        'register_date' => ['required', 'date'],
                        'stock_out' =>['required_if:stock_in,==,0','numeric'],
                        'stock_in' =>['required_if:stock_out,==,0','numeric'],
                        'type' =>['required', Rule::in($type)],
                        'range_age_type' =>['required',Rule::in($range_age)],
                        'ticket_id' => ['required','exists:tickets,id'],
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
