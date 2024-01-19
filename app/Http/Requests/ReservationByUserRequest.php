<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;

class ReservationByUserRequest extends FormRequest
{
       /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
{
    //    $user = auth()->user();

    //    $isAdmin = $user && $user->roles->first()->name === 'admin' || $user && $user->roles->first()->name === 'super admin';
   
    //    if ($isAdmin) {
    //        return true;
    //    }
   
    //    $restrictedFields = ['discount_amount', 'vendor_comissions', 'payment_type'];
   
    //    foreach ($this->all() as $field => $value) {
    //        if (in_array($field, $restrictedFields) && !is_null($value)) {
    //            return false;
    //        }
    //    }
   
       return true;
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $user = auth()->user();
        $isAdmin = $user && $user->roles->first()->name === 'admin' || $user && $user->roles->first()->name === 'super admin';
        $type = Reservation::PAYMENT_TYPE;
        $refund_status = Reservation::TICKET_REFUNDED_STATUS;
        $type_price_item = ReservationItem::TYPE_PRICE;
        $sub_item_status = ReservationSubItem::SEND_STATUS;
        switch($this->method()) {
            case 'GET':
                {
                    return [];
                } break;

            case 'POST':
                {                   
                    return [
                        'fullname' => ['required'],
                        'first_name' => ['required'],
                        'last_name' => ['required'],
                        'email' => ['required','email','confirmed'],
                        'email_confirmation' => ['required','email','max:255'],
                        'departure_date' => ['nullable','date'],
                        'phone' => ['required'],
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
                        'items.*.sub_items.*.seating_info' => ['nullable'],
                        'discount_amount' => ($isAdmin) ? ['required', 'numeric', 'min:0'] : ['exclude'],
                        'vendor_comissions' => ($isAdmin) ? ['array', 'nullable'] : ['exclude'],
                        'vendor_comissions.*.user_id' => ($isAdmin) ? ['required'] : ['exclude'],
                        'vendor_comissions.*.type' => ($isAdmin) ? ['required', Rule::in(['AP', 'AR'])] : ['exclude'],
                        'vendor_comissions.*.comission_amount' => ($isAdmin) ? ['required', 'numeric'] : ['exclude'], 
                        'payment_type' => ($isAdmin) ? ['required', Rule::in($type)] : ['exclude'],
                        'credit' => ($isAdmin) ? ['required_if:payment_type,Cash'] : ['exclude'], 
                        'stripe_token' => ['required_if:payment_type,Credit Card,null']
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
                        'items.*.refund_status' => ['nullable',Rule::in($refund_status)],
                        'items.*.refund_sent_date' =>  ['nullable','date'], 
                        'items.*.sub_items' => 'array',
                        'items.*.sub_items.*.rq_schedule_datetime' => 'nullable|date',
                        'items.*.sub_items.*.ticket_id' => ['required','exists:tickets,id'],
                        'items.*.sub_items.*.id' => 'nullable|exists:reservation_sub_items,id',
                        'items.*.sub_items.*.refund_status' => ['nullable',Rule::in($refund_status)],
                        'items.*.sub_items.*.refund_sent_date' => ['nullable','date'],
                        'items.*.sub_items.*.ticket_sent_status' => ['nullable',Rule::in($sub_item_status)],
                        'items.*.sub_items.*.seating_info' => ['nullable'],
                        'payment_type' => ($isAdmin) ? ['nullable', Rule::in($type)] : ['exclude'],
                        'credit' => ($isAdmin) ? ['nullable'] : ['exclude'], 
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
