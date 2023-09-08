<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TicketStock;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class DestroyMultipleUploadedRequest extends FormRequest
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
        $stocks = TicketStock::where('ticket_id', $this->route('ticket_id'))
            ->where('file_name_upload', $this->file_name_upload)
            ->where('range_age_type', $this->range_age_type)
            ->where('status', TicketStock::STATUS['USED'])
            ->count();

        if($stocks > 0){
            throw ValidationException::withMessages([
                'ticket_stock' => ["You can't delete the records as there are some tickets sold"]
            ]);
        }
        return [
            'file_name_upload' => ['required', 'exists:ticket_stocks,file_name_upload'],
            'range_age_type' => ['required', Rule::in(TicketStock::RANGE_AGE)]
        ];
    }
}
