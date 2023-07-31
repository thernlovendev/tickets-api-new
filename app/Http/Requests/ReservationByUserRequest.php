<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Reservation;
use App\Models\ReservationItem;

class ReservationByUserRequest extends FormRequest
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
        $type = Reservation::PAYMENT_TYPE;
        $type_price_item = ReservationItem::TYPE_PRICE;
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {                   
                    return [
                        'first_name' => ['required'],
                        'last_name' => ['required'],
                        'email' => ['required','email','confirmed'],
                        'email_confirmation' => ['required','email','max:255'],
                        'departure_date' => ['nullable','date'],
                        'phone' => ['required'],
                        'discount_amount' => ['nullable','numeric','min:0'],
                        'items.*.category_id' => ['nullable','exists:categories,id'],
                        'items.*.subcategory_id' => ['nullable','exists:subcategories,id'],
                        'items.*.price_list_id' => ['nullable','exists:price_lists,id'],
                        'items.*.adult_child_type' => ['required',Rule::in($type_price_item)],
                        'items.*.child_age' => ['nullable'],
                        'items.*.price' => ['numeric'],
                        'items.*.quantity' => ['integer'],
                        'items.*.sub_items' => ['array'],
                        'items.*.sub_items.*.rq_schedule_datetime' => ['nullable','date'],
                        'items.*.sub_items.*.ticket_id' => ['required','exists:tickets,id'],
                        'items.*.sub_items.*.refund_status' => ['nullable'],
                        'stripe_token' => ['required'],
                    ];
                } break;

                case 'PUT':{
                    return [
                        'items.*.id' => 'nullable','exists:reservation_items,id',
                        'items.*.category_id' => 'nullable','exists:categories,id',
                        'items.*.subcategory_id' =>'nullable','exists:subcategories,id',
                        'items.*.price_list_id' => 'nullable','exists:price_lists,id',
                        'items.*.adult_child_type' => 'required',
                        'items.*.child_age' => 'nullable',
                        'items.*.price' => 'required|numeric',
                        'items.*.quantity' => 'integer',
                        'items.*.sub_items' => 'array',
                        'items.*.sub_items.*.rq_schedule_datetime' => 'nullable|date',
                        'items.*.sub_items.*.ticket_id' => ['required','exists:tickets,id'],
                        'items.*.sub_items.*.id' => 'nullable|exists:reservation_sub_items,id',
                        'items.*.sub_items.*.refund_status' => 'nullable',
                        'stripe_token' => ['nullable'],
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
