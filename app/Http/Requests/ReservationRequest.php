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
        $refund_status = Reservation::TICKET_REFUNDED_STATUS;
        $type_price_item = ReservationItem::TYPE_PRICE;

        if(count($this->vendor_comissions) == 1 && $this->vendor_comissions[0]['type'] == null && $this->vendor_comissions[0]['user_id'] == 0){
            $ignore_vendor = false;
        } else {
            $ignore_vendor = true;
        }
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
                        'vendor_comissions' => ['array','nullable'],
                        'vendor_comissions.*.user_id' => ['required'],
                        'vendor_comissions.*.type' => ['required', Rule::in('AP', 'AR')],
                        'vendor_comissions.*.comission_amount' => ['required','numeric'],
                        'payment_type' => ['required',Rule::in($type)],
                        'stripe_token' => ['required_if:payment_type,Credit Card'],
                        'credit' => ['required_if:payment_type,Cash']
                    ];
                } break;

            case 'PUT':{
                return [
                    'memo' => ['nullable','max:800'],
                    'history' => ['nullable','max:800'],
                    'items.*.id' => 'nullable','exists:reservation_items,id',
                    'items.*.category_id' => 'required','exists:categories,id',
                    'items.*.subcategory_id' =>'nullable','exists:subcategories,id',
                    'items.*.price_list_id' => 'nullable','exists:price_lists,id',
                    'items.*.adult_child_type' => 'required',
                    'items.*.child_age' => 'nullable',
                    'items.*.price' => 'required|numeric',
                    'items.*.quantity' => 'integer',
                    'items.*.refund_status' => ['nullable',Rule::in($refund_status)],
                    'items.*.refund_sent_date' =>  ['nullable','date'], 
                    'items.*.ticket_sent_status' => 'nullable',
                    'items.*.sub_items' => 'array',
                    'items.*.sub_items.*.id' => 'nullable|exists:reservation_sub_items,id',
                    'items.*.sub_items.*.rq_schedule_datetime' => 'nullable|date',
                    'items.*.sub_items.*.ticket_id' => ['required','exists:tickets,id'],
                    'items.*.sub_items.*.refund_status' => ['nullable',Rule::in($refund_status)],
                    'items.*.sub_items.*.refund_sent_date' => ['nullable'],
                    'vendor_comissions' => $ignore_vendor ? ['array','nullable'] : ['exclude'],
                    'vendor_comissions.*.id' => $ignore_vendor ? ['nullable'] : ['exclude'],
                    'vendor_comissions.*.user_id' => $ignore_vendor ? ['required','exists:users,id'] : ['exclude'],
                    'vendor_comissions.*.type' => $ignore_vendor ? ['required', Rule::in('AP', 'AR')] : ['exclude'],
                    'vendor_comissions.*.comission_amount' => $ignore_vendor ? ['required','numeric'] : ['exclude'],
                    'payment_type' => ['nullable',Rule::in($type)],
                    'stripe_token' => ['required_if:payment_type,Credit Card'],
                    'credit' => ['required_if:payment_type,Cash']
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
