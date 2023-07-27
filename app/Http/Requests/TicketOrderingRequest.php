<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketOrderingRequest extends FormRequest
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
        
        return [
            '*.ticket_id'=> ['exists:tickets,id', 'distinct'],
            '*.order' => ['distinct', 'integer', 'min:1']   
        ];
    }
}
