<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Ticket;
use App\Models\TicketSchedule;
use App\Models\TicketPrice;

class TicketScheduleRequest extends FormRequest
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
        $days = TicketSchedule::DAYS;
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
                    'date_start' => 'date|required',
                    'date_end' => 'date|required',
                    'max_people' => 'integer|required',
                    'week_days' => ['array','required', Rule::in($days)],
                    'time' => ['required','date_format:H:i'],
                    'ticket_schedule_exceptions' => ['nullable'],
                    'ticket_schedule_exceptions.*.id' => ['nullable'],
                    'ticket_schedule_exceptions.*.date' => ['required','date','date_format:Y-m-d'],
                    'ticket_schedule_exceptions.*.max_people' => ['required','integer','min:0'],
                    'ticket_schedule_exceptions.*.show_on_calendar' => ['required','boolean']
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
