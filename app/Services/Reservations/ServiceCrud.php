<?php

namespace App\Services\Reservations;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\Ticket;
use App\Models\Subcategory;
use App\Models\PriceList;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;
use App\Services\Reservations\ServiceCashPayment;
use App\Services\Reservations\ServiceCreditCard;
// use App\Services\Stripe\Service as ServiceStripe;
use App\Utils\ModelCrud;
use Illuminate\Validation\ValidationException;
use App\Exceptions\StripeTokenFailException;
use Mail;
use Carbon\Carbon;

class ServiceCrud
{
	public static function create($data)
	{
            // if($data['payment_type'] == 'Credit Card'){
            //     $service = new ServiceStripe();
        
            //     $credit_card = collect($data)->only('credit_number', 'cvc','exp_month','exp_year');
            //     $token_credit_card = $service->createTokenCreditCard($credit_card); 
                
            //     $data['token_id'] = $token_credit_card['id'];
            // }
            do {
                $order_number =  mt_rand(1000000, 9999999);
                settype($order_number, 'string');
                // $code = $prefix.$order_number;
            } while (Reservation::where("order_number", "=", $order_number)->exists());

            $data['order_number'] = $order_number;

            $created_by = Auth::user()->name;

            $data['created_by'] = $created_by;
            
            $reservation = Reservation::create($data);

            foreach ($data['items'] as $item) {
                $subcategory = Subcategory::find($item['subcategory_id']);
                    
                    $reserve_item = ReservationItem::create(
                        [
                          'reservation_id' => $reservation->id,
                          'category_id' => $item['category_id'],
                          'subcategory_id' => $item['subcategory_id'],
                          'price_list_id' => $item['price_list_id'],
                          'adult_child_type' => $item['adult_child_type'],
                          'child_age' => $item['child_age'],
                          'price' => $item['price'],
                          'addition' => 0,
                          'quantity' => $item['quantity'],
                          'total' =>  0
                        ]); 

                        foreach($item['sub_items'] as $index => $sub_item)
                        {
                           $ticket = Ticket::find($sub_item['ticket_id']);
                           
                           switch ($ticket->ticket_type) {
                            case Ticket::TYPE['REGULAR']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                break;

                            case Ticket::TYPE['BAR_QR']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TO_DO'];
                                break;
                            case Ticket::TYPE['GUIDE_TOUR']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                
                                break;
                            case Ticket::TYPE['HARD_COPY']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                break;
                            
                           }

                           $item['sub_items'][$index]['addition'] = $ticket->additional_price_amount;
                        }

                        
                        $new_price = $item['price'];
                        
                        // $price_list = $subcategory->pricesLists()->where('id',$item['price_list_id'])->first();
                        
                        // if($price_list){
                        //     if($item['adult_child_type'] == ReservationItem::TYPE_PRICE['ADULT']){
                        //         $new_price = $price_list['adult_price'];
                        //     } else {
                        //         $new_price = $price_list['child_price'];
                        //     }
                        // }

                        $addition = collect($item['sub_items'])->sum('addition');
                        $total = ($new_price + $addition) * $item['quantity'];
                        $reserve_item->update(['price' => $new_price,'addition' => $addition, 'total' => $total]);

                        $reserve_item->reservationSubItems()->createMany($item['sub_items']);
                }

                $reservation->vendorComissions()->createMany($data['vendor_comissions']);
                $reservation->status = Reservation::STATUS['NO_PAID'];
                $reservation->ticket_sent_status = Reservation::TICKET_SENT_STATUS['TO_DO'];
            
                $reservation->subtotal = $reservation->reservationItems()->sum('total');
                $reservation->total = round($reservation->subtotal - $reservation->discount_amount, 2);

                $reservation->save();

                $data['total'] = $reservation->total;
                if($data['payment_type'] == Reservation::PAYMENT_TYPE['CASH']){
                    $response = ServiceCashPayment::create($reservation, $data);
                }  else{
                    $response = ServiceCreditCard::create($reservation, $data);
                }

                $template = Template::where('title','After Payment Completed')->first();
        
                if($template->subject == 'default'){
                    $subject = "Payment Completed";
                } else {
                    $subject = $template->subject;
                }
                
                // Mail::send('email.paymentCompleted', ['fullname' => $reservation->customer_name_en, 'amount'=> $data['total'], 'template' => $template], function($message) use($reservation, $template, $subject){
                //     $message->to($reservation->email);
                //     $message->subject($subject);
                // });
            

            return $reservation->load(['reservationItems.reservationSubItems','vendorComissions']);

	}

	public static function update($data, $reservation_old)
	{
            $reservation_old->update($data);

            $total_old = $reservation_old->total;

            ModelCrud::deleteUpdateOrCreate($reservation_old->vendorComissions(), $data['vendor_comissions']);

            foreach ($data['items'] as $item){
                $item_model = $reservation_old->reservationItems()->where('id', $item['id'])->first();
                $item['total'] = 0;
                $item['addition'] = 0;
                
                if($item_model){
                    $item_model->update($item);
                } else {
                    $item_model = $reservation_old->reservationItems()->create($item);
                }
                //Set the additional from the ticket and the status
                
                foreach($item['sub_items'] as  $index => $sub_item){
                    $ticket = Ticket::find($sub_item['ticket_id']);

                    if(!isset($sub_item['id'])){
                        switch ($ticket->ticket_type) {
                            case Ticket::TYPE['REGULAR']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                break;
    
                            case Ticket::TYPE['BAR_QR']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TO_DO'];
                                break;
                            case Ticket::TYPE['GUIDE_TOUR']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                
                                break;
                            case Ticket::TYPE['HARD_COPY']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                break;
                            
                        }

                    } 

                    if($sub_item['rq_schedule_datetime'] !== null){
                        $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                    } else {
                        $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                    }
                    
                    $item['sub_items'][$index]['addition'] = $ticket->additional_price_amount;

                    if($sub_item['refund_status'] !== null){
                        $item['sub_items'][$index]['refund_sent_date'] = Carbon::now();
                    }
                }

                $addition = collect($item['sub_items'])->sum('addition');
                $total = ($item['price'] + $addition) * $item['quantity'];

                $item_model->update(['addition' => $addition, 'total' => $total]);
                
                ModelCrud::deleteUpdateOrCreate($item_model->reservationSubItems(), $item['sub_items']);
            }

            $reservation_old->subtotal = $reservation_old->reservationItems()->sum('total');
            
            $reservation_old->total = round($reservation_old->subtotal - $reservation_old->discount_amount, 2);

            $reservation_old->order_date = Carbon::now()->format('Y-m-d');

            $reservation_old->save();

            if($reservation_old->total - $total_old > 0){    
                            
                if($data['payment_type'] == 'Credit Card'){
                
                    $validator = Validator::make($data,[
                        'payment_type' => ['required',Rule::in(Reservation::PAYMENT_TYPE)],
                        'stripe_token' => ['required_if:payment_type,Credit Card'],

                        // 'credit_number'=> ['required_if:payment_type,Credit Card','min:14','max:19','string'],
                        // 'exp_month'=> ['required_if:payment_type,Credit Card','integer','min:1','max:12'],
                        // 'exp_year'=> ['required_if:payment_type,Credit Card','integer'],
                        // 'cvc'=> ['required_if:payment_type,Credit Card','min:3','max:4','string']
                    ]);
                    
                    if( $validator->fails()){
                        throw new StripeTokenFailException($validator->messages());
                    }
                   
                    // $service = new ServiceStripe();
                    // $credit_card = collect($data)->only('credit_number', 'cvc','exp_month','exp_year');
                    // $token_credit_card = $service->createTokenCreditCard($credit_card); 
                    // $data['token_id'] = $token_credit_card['id'];
                    
                    $data = $validator->validate();

                } else {
                    $validator = Validator::make($data, [
                        'payment_type' => ['required',Rule::in(Reservation::PAYMENT_TYPE)],
                        'credit' => ['required_if:payment_type,Cash'],
                    ]);
                    
                    if( $validator->fails() ){
                        // return $validator;
                        throw ValidationException::withMessages([
                            'errors' => $validator->errors()
                        ]);
                    }
                    $data = $validator->validate();
                }

                $data['total'] = $reservation_old->total - $total_old;
                if($data['payment_type'] == Reservation::PAYMENT_TYPE['CASH']){
                    $response = ServiceCashPayment::create($reservation_old, $data);
                }  else{
                    $response = ServiceCreditCard::create($reservation_old, $data);
                }

                $template = Template::where('title','After Upgraded Order')->first();
        
                if($template->subject == 'default'){
                    $subject = "Order Upgraded";
                } else {
                    $subject = $template->subject;
                }

                // Mail::send('email.upgradedOrder', ['fullname' => $reservation_old->customer_name_en, 'amount' => $data['total']], function($message) use ($reservation_old, $subject){
                //     $message->to($reservation_old->email);
                //     $message->subject($subject);
                // });
                
            }

            
          return $reservation_old->load('reservationItems.reservationSubItems','vendorComissions');
	}

	public static function delete($reservation)
	{
        // $reservation->delete();
        // return $reservation;
    }

    public static function response($reservation)
    {
        return $reservation;
    }
}