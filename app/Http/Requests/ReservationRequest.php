<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Reservation;
use App\Models\ReservationItem;
class ReservationRequest extends FormRequest
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

                    // if($reservation->status !== Reservation::STATUS['NO_PAID']){
                    //     throw ValidationException::withMessages([
                    //         'errors' => ['A payment has already been made, reservation status: '.$reservation->status]
                    //     ]);
                    // }
                    
                   
                    return [
                        'email' => ['required','email'],
                        'order_date' => ['required','date'],
                        'departure_date' => ['required','date'],
                        'customer_name_en' => ['required'],
                        'customer_name_kr' => ['required'],
                        'phone' => ['nullable'],
                        'discount_amount' => ['numeric'],
                        'items.*.category_id' => ['required','exists:categories,id'],
                        'items.*.subcategory_id' => ['required','exists:subcategories,id'],
                        'items.*.price_list_id' => ['nullable','exists:price_lists,id'],
                        'items.*.adult_child_type' => ['required',Rule::in($type_price_item)],
                        'items.*.child_age' => ['nullable'],
                        'items.*.price' => ['numeric'],
                        'items.*.quantity' => ['integer'],
                        'items.*.sub_items' => ['array'],
                        'items.*.sub_items.*.rq_schedule_datetime' => ['nullable','date'],
                        'items.*.sub_items.*.ticket_id' => ['required','exists:tickets,id'],
                        'items.*.sub_items.*.refund_status' => ['nullable'],
                        'vendor_comissions' => ['array','nullable'],
                        'vendor_comissions.*.user_id' => ['required'],
                        'vendor_comissions.*.type' => ['required', Rule::in('AP', 'AR')],
                        'vendor_comissions.*.comission_amount' => ['required','numeric'],
                        'payment_type' => ['required',Rule::in($type)],
                        'credit' => ['required_if:payment_type,Cash'],
                        'token_stripe'=> ['required_if:payment_type,Credit Card']
                    ];
                } break;

            case 'PUT':{
                return [
                    'memo' => ['max:800'],
                    'history' => ['max:800'],
                    'items.*.id' => 'nullable','exists:reservation_items,id',
                    'items.*.category_id' => 'required','exists:categories,id',
                    'items.*.subcategory_id' =>'required','exists:subcategories,id',
                    'items.*.price_list_id' => 'nullable','exists:price_lists,id',
                    'items.*.adult_child_type' => 'required',
                    'items.*.child_age' => 'nullable',
                    'items.*.price' => 'required|numeric',
                    'items.*.quantity' => 'integer',
                    'items.*.sub_items' => 'array',
                    'items.*.sub_items.*.rq_schedule_datetime' => 'nullable|date',
                    'items.*.sub_items.*.ticket_id' => 'required|exists:tickets,id',
                    'items.*.sub_items.*.id' => 'nullable|exists:reservation_sub_items,id',
                    'items.*.sub_items.*.refund_status' => 'nullable',
                    'vendor_comissions' => 'array|nullable',
                    'vendor_comissions.*.user_id' => 'required',
                    'vendor_comissions.*.type' => ['required', Rule::in('AP', 'AR')],
                    'vendor_comissions.*.comission_amount' => 'required|numeric',
                    'payment_type' => ['nullable',Rule::in($type)],
                    'credit' => ['required_if:payment_type,Cash'],
                    'token_stripe'=> 'required_if:payment_type,Credit Card'
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
