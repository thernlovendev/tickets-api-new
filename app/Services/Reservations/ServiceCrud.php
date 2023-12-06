<?php

namespace App\Services\Reservations;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\Ticket;
use App\Models\TicketSchedule;
use App\Models\TicketStock;
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
use App\Exceptions\FailException;
use Mail;
use Carbon\Carbon;
use PDF;
use App\Mail\InvoiceEmail;
use App\Services\Reservations\ServiceMemo;

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
                $order_number =  mt_rand(100000000, 999999999);
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
                                if($sub_item['rq_schedule_datetime'] !== null){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                    $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } else {
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                }
                                break;

                            case Ticket::TYPE['BAR_QR']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                
                                $quantity = $reserve_item->quantity;
                                $range_age = $reserve_item->adult_child_type;
                                $ticket_id = $ticket->id;

                                $now = Carbon::now()->format('Y-m-d H:i:s');

                                $stocks = TicketStock::where('status',TicketStock::STATUS['VALID'])
                                        ->where('expiration_date','>', $now)
                                        ->where('ticket_id', $ticket_id)
                                        ->where('range_age_type',$range_age)
                                        ->take($quantity)
                                        ->get();

                                // if(count($stocks) < $quantity){
                                //     $message = 'The inventory of ticket "'.$ticket->title_en.'", of type "'.$range_age.'", has been exceeded, the available quantity is '.count($stocks);
                                //     throw new \Exception($message);
                                // }
                                break;
                            case Ticket::TYPE['GUIDE_TOUR']:
                                if($item['sub_items'][$index]['rq_schedule_datetime'] !== null){
                                    $selectedDateTime = Carbon::parse($item['sub_items'][$index]['rq_schedule_datetime']);
                                    $ticketId = $item['sub_items'][$index]['ticket_id'];


                                    $ticketSchedule = TicketSchedule::where('ticket_id', $ticketId)
                                                                    ->where('date_start', '<=', $selectedDateTime->format('Y-m-d'))
                                                                    ->where('date_end', '>=', $selectedDateTime->format('Y-m-d'))
                                                                    ->whereJsonContains('week_days', [ucfirst(strtolower($selectedDateTime->englishDayOfWeek))]) // Verifica si el día de la semana coincide
                                                                    ->where('time', $selectedDateTime->format('H:i:s'))
                                                                    ->first();

                                    if ($ticketSchedule) {
                                        $matches = ReservationSubItem::where('rq_schedule_datetime', $selectedDateTime->format('Y-m-d H:i'))->where('ticket_id',$ticketId)->get();
                                        $sold_tickets = 0;
                                        
                                        foreach ($matches as $match) {
                                            $sold_tickets += $match->reservationItem->quantity;
                                        }
                                        
                                        $exception_schedule = $ticketSchedule->ticketScheduleExceptions()->whereDate('date', $selectedDateTime->toDateString());
                                        
                                        if($exception_schedule->count() == 0){
                                            $availableSlots = $ticketSchedule->max_people - $sold_tickets;
                                        } else {
                                            $exception = $exception_schedule->first();
                                            $availableSlots = $exception->max_people - $sold_tickets;
                                        }
                                        if ($availableSlots >= $item['quantity']) {
                                            $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                            $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                        } else {
                                            $message = 'Your purchase exceeds the number of available seats, you can only request a maximum '.$availableSlots.' of the ticket '.$ticket->title_en;
                                            throw new \Exception($message);
                                        }
                                    } else {
                                        $message = 'There is no schedule assigned to the date and time entered, please check again';
                                        throw new \Exception($message);
                                    }
                                    
                                }else {
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                }
                                
                                break;
                            case Ticket::TYPE['HARD_COPY']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                break;
                            case Ticket::TYPE['SIM_CARD']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                break;
                            
                            case Ticket::TYPE['MUSICAL_SHOW']:

                                do {
                                    $new_number =  mt_rand(10000000, 99999999);
                                    settype($new_number, 'string');
                                } while (Reservation::where("order_number", "=", '3'.$new_number)->exists());

                                $reservation->update(['order_number'=> '3'.$new_number]);
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                break;
                            
                           }

                           if($item['price_list_id']){
                                if ($ticket->additional_price_type == 'Premium'){
                                $item['sub_items'][$index]['addition'] = $ticket->premium_amount;
        
                                } else if ($ticket->additional_price_type == 'Premium S'){
                                $item['sub_items'][$index]['addition'] = $ticket->premium_s_amount;
                                
                                }
                            }
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

                if($reservation->status == Reservation::STATUS['NO_PAID']){
                    throw new \Exception($response);
                }

                $template = Template::where('title','After Payment Completed')->first();
        
                if($template->subject == 'default'){
                    $subject = '[타마스] Order Confirmation: # '.$reservation->order_number.' '.$reservation->customer_name_en." Payment Completed";
                } else {
                    $subject = '[타마스] Order Confirmation: # '.$reservation->order_number.' '.$reservation->customer_name_en." ".$template->subject;
                }

                
                Mail::send('email.paymentCompleted', ['fullname' => $reservation->customer_name_en, 'amount'=> $data['total'], 'template' => $template], function($message) use($reservation, $template, $subject, $data){
                    $message->to($reservation->email);
                    $message->subject($subject);
                    $fullname = $reservation->customer_name_en;
                    $orderNumber = $reservation->order_number;
                    $orderDate = $reservation->created_at->format('Y-m-d g:i A');
                    $discount = $reservation->discount_amount;
                    $amount = $data['total'];
                    $iconDashboardSquare = public_path('images/dashboard-square.svg');
                    $iconBookOpen = public_path('images/book-open.svg');
                    $iconDollarCircle = public_path('images/dollar-circle.svg');
                    $iconMessage = public_path('images/message.svg');
                    $iconLocation = public_path('images/location.svg');
                    $reservationItems = $reservation->reservationItems()->with('reservationSubItems.ticket:id,title_en', 'subcategory:id,name', 'category:id,name','priceList:id,product_type')->get();

                    $cash_type =false; 
                    $credit_type =false; 

                    if($data['payment_type'] == Reservation::PAYMENT_TYPE['CASH']){
                        $bill_data = $reservation->reservationCashPayments()->get()->last();
                        $cash_type =true; 
                    } else {
                        $bill_data = $reservation->reservationCreditCardPayments()->get()->last();
                        $credit_type =true; 
                    }

                    if($data['created_by'] == 'Customer'){
                        $auth = false;
                    } else {
                        $auth = true;
                    }

                    $pdf = PDF::loadView('invoicePayment', compact('iconDashboardSquare','iconBookOpen','iconDollarCircle','iconMessage','iconLocation','fullname','amount', 'orderNumber','orderDate','reservationItems','discount','cash_type','credit_type','bill_data', 'auth'));

                    $message->attachData($pdf->output(), 'Tamice-ticket.pdf');
                });

            

            return $reservation->load(['reservationItems.reservationSubItems','vendorComissions']);

	}

	public static function update($data, $reservation_old)
	{
            $reservation_old->update($data);

            $total_old = $reservation_old->total;

            if(isset($data['vendor_comissions'])){
                ModelCrud::deleteUpdateOrCreate($reservation_old->vendorComissions(), $data['vendor_comissions']);
            }

            foreach ($data['items'] as $item){
                $item_model = $reservation_old->reservationItems()->where('id', $item['id'])->first();
                // $item['total'] = 0;
                $item['addition'] = 0;
                
                if($item_model){

                    $item_model->fill($item);
                    if($item_model->isDirty()){
                        ServiceMemo::create($item_model, 'update', $reservation_old, 'Item');
                    }
                    $item_model->save();

                } else {
                    $item_model = $reservation_old->reservationItems()->create($item);
                    ServiceMemo::create($item_model, 'create', $reservation_old, 'Item');

                }
                //Set the additional from the ticket and the status
                
                foreach($item['sub_items'] as  $index => $sub_item){
                    $ticket = Ticket::find($sub_item['ticket_id']);

                    if(!isset($sub_item['id'])){
                        switch ($ticket->ticket_type) {
                            case Ticket::TYPE['REGULAR']:
                                if($sub_item['rq_schedule_datetime'] !== null){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                    $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } else {
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                }
                                break;
    
                            case Ticket::TYPE['BAR_QR']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                break;
                            case Ticket::TYPE['GUIDE_TOUR']:
                                if($sub_item['rq_schedule_datetime'] !== null){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                    $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } else {
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                }
                                
                                break;
                            case Ticket::TYPE['HARD_COPY']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                break;

                            case Ticket::TYPE['SIM_CARD']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                break;
                            
                            case Ticket::TYPE['MUSICAL_SHOW']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                break;
                        }

                    } else {
                        switch ($ticket->ticket_type) {
                            case Ticket::TYPE['REGULAR']:
                                $old_sub_item = ReservationSubItem::find($sub_item['id']);
                                if($old_sub_item['rq_schedule_datetime'] == null && $old_sub_item['rq_schedule_datetime'] !== $sub_item['rq_schedule_datetime']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                    $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } 

                                $old_sub_item = ReservationSubItem::find($sub_item['id']);
                                
                                if($old_sub_item['ticket_sent_status'] == ReservationSubItem::SEND_STATUS['TBD'] && $old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['REFUNDED']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['REFUNDED'];
                                } else if ($old_sub_item['ticket_sent_status'] == ReservationSubItem::SEND_STATUS['SENT'] && $sub_item['refund_status'] = ReservationSubItem::SEND_STATUS['REFUNDED'] || $sub_item['refund_status'] = ReservationSubItem::SEND_STATUS['IN_PROGRESS']){
                                    $message = 'The ticket "'.$ticket->title_en.'" with a sent status of Item ID: '.$item.'  cannot be placed as refunded"';
                                    throw new \Exception($message);
                                }

                                break;
    
                            case Ticket::TYPE['BAR_QR']:
                                $old_sub_item = ReservationSubItem::find($sub_item['id']);
                                if($old_sub_item['ticket_sent_status'] !== ReservationSubItem::SEND_STATUS['SENT'] && $old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['REFUNDED']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['REFUNDED'];
                                }
                                break;
                            case Ticket::TYPE['GUIDE_TOUR']:
                                $old_sub_item = ReservationSubItem::find($sub_item['id']);
                                if($old_sub_item['rq_schedule_datetime'] !== $sub_item['rq_schedule_datetime']){
                                    if($sub_item['rq_schedule_datetime'] !== null){
                                        $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                        $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                    } else {
                                        $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                    }
                                }
                                
                                break;
                            case Ticket::TYPE['HARD_COPY']:
                                $old_sub_item = ReservationSubItem::find($sub_item['id']);
                                if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['PICKED_UP']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                    $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } else if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['REFUNDED']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['REFUNDED'];
                                    $item['sub_items'][$index]['refund_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } else if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == null){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                }else if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['IN_PROGRESS']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                }
                                break;

                            case Ticket::TYPE['SIM_CARD']:
                                $old_sub_item = ReservationSubItem::find($sub_item['id']);
                                if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['PICKED_UP']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                    $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } else if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['REFUNDED']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['REFUNDED'];
                                    $item['sub_items'][$index]['refund_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } else if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == null){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                }else if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['IN_PROGRESS']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                }
                                break;
                            
                            case Ticket::TYPE['MUSICAL_SHOW']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                break;
                        }
                    }
                    
                    $item['sub_items'][$index]['addition'] = $ticket->additional_price_amount;

                    if($sub_item['refund_status'] !== null){
                        $item['sub_items'][$index]['refund_sent_date'] = Carbon::now();
                    }
                }

                $addition = collect($item['sub_items'])->sum('addition');
                $total = ($item['price'] + $addition) * $item['quantity'];

                $item_model->update(['addition' => $addition, 'total' => $total]);
                
                ServiceMemo::deleteUpdateOrCreateMemo($item_model->reservationSubItems(), $item['sub_items'], $reservation_old);
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
                    if (array_key_exists('original', $response) && array_key_exists('errors', $response->original)) {
                        throw new \Exception($response);
                    } 
                    
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