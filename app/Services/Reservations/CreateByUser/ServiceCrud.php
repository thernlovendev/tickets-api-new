<?php

namespace App\Services\Reservations\CreateByUser;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\Ticket;
use App\Models\TicketSchedule;
use App\Models\TicketStock;
use App\Models\Template;
use App\Models\Subcategory;
use App\Models\PriceList;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\Reservations\ServiceCashPayment;
use App\Services\Reservations\ServiceCreditCard;
// use App\Services\Stripe\Service as ServiceStripe;
use App\Utils\ModelCrud;
use Illuminate\Validation\ValidationException;
use App\Exceptions\StripeTokenFailException;
use Carbon\Carbon;
use App\Exceptions\FailException;
use PDF;
use PDF2;
use Mail;
use App\Mail\InvoiceEmail;

class ServiceCrud
{
	public static function create($data)
	{
            do {
                $order_number =  mt_rand(100000000, 999999999);
                settype($order_number, 'string');
            } while (Reservation::where("order_number", "=", $order_number)->exists());
            
            $data['order_number'] = $order_number;

            $data['customer_name_kr'] = $data['fullname'];
            $data['customer_name_en'] = $data['first_name'].' '.$data['last_name'];
            
            $created_by = Auth::user();

            if($created_by){
                $rol = $created_by->roles()->first();
                if($rol->name == 'admin' || $rol->name == 'super admin'){
                    $data['created_by'] = $created_by->name;
                }else{
                    $data['created_by'] = 'Customer';
                }
            } else {
                $data['created_by'] = 'Customer';
            }

            $data['order_date'] = Carbon::now()->format('Y-m-d');
            
            $user = auth()->user();
            $isAdmin = $user && $user->roles->first()->name === 'admin' || $user && $user->roles->first()->name === 'super admin';

            if(!$isAdmin){
                $data['payment_type'] = 'Credit Card';
            }
            
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
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                break;

                            case Ticket::TYPE['SIM_CARD']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                break;
                            
                            case Ticket::TYPE['CITY_EXPLORE_PASS']:
                            $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
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

                        $addition = collect($item['sub_items'])->sum('addition');
                        $total = ($new_price + $addition) * $item['quantity'];
                        $reserve_item->update(['price' => $new_price,'addition' => $addition, 'total' => $total]);

                        $reserve_item->reservationSubItems()->createMany($item['sub_items']);
                }

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
                    $subject = '타미스 주문번호 # '.$reservation->order_number.' '.$reservation->customer_name_kr." Payment Completed";
                } else {
                    $subject = '타미스 주문번호 # '.$reservation->order_number.' '.$reservation->customer_name_kr." ".$template->subject;
                }

                
                Mail::send('email.paymentCompleted', ['fullname' => $reservation->customer_name_kr, 'amount'=> $data['total'], 'template' => $template], function($message) use($reservation, $template, $subject, $data){
                    $message->to($reservation->email);
                    $message->subject($subject);
                    $fullname = $reservation->customer_name_kr;
                    $orderNumber = $reservation->order_number;
                    $orderDate = $reservation->created_at->format('Y-m-d g:i A');
                    $discount = $reservation->discount_amount;
                    $email_customer = $reservation->email;
                    $user_signed = User::where('email',$reservation->email)->first();
                    $name_customer =$user_signed ? $user_signed->firstname.' '.$user_signed->lastname : $reservation->customer_name_kr;
                    $amount = $data['total'];
                    $iconDashboardSquare = public_path('images/dashboard-square.svg');
                    $iconBookOpen = public_path('images/book-open.svg');
                    $iconDollarCircle = public_path('images/dollar-circle.svg');
                    $iconMessage = public_path('images/message.svg');
                    $iconLocation = public_path('images/location.svg');
                    $reservationItems = $reservation->reservationItems()->with('reservationSubItems.ticket:id,title_kr,ticket_type', 'subcategory:id,name', 'category:id,name','priceList:id,product_type')->get();

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

                    $pdf = PDF2::loadView('invoicePayment', compact('iconDashboardSquare','iconBookOpen','iconDollarCircle','iconMessage','iconLocation','fullname','amount', 'orderNumber','orderDate','reservationItems','discount','cash_type','credit_type','bill_data', 'auth','name_customer','email_customer'));

                    $message->attachData($pdf->output(), 'Tamice-Receipt.pdf');
                });

            return $reservation->load(['reservationItems.reservationSubItems','vendorComissions']);

	}

    public static function update($data, $reservation_old)
	{
        
            $user = auth()->user();
            $isAdmin = $user && $user->roles->first()->name === 'admin' || $user && $user->roles->first()->name === 'super admin';

            if(!$isAdmin){
                $data['payment_type'] = 'Credit Card';
            }

            $total_old = $reservation_old->total;

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
                    $previous_item = ReservationSubItem::find($sub_item['id']);

                    if(!isset($sub_item['id']) || (isset($sub_item['id']) && $previous_item['ticket_id'] != $item['sub_items'][$index]['ticket_id']) ){
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
                                $quantity = $item_model->quantity;
                                $range_age = $item_model->adult_child_type;
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
                                if($sub_item['rq_schedule_datetime'] !== null){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                    $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } 
                                
                                break;
                            case Ticket::TYPE['HARD_COPY']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                break;

                            case Ticket::TYPE['SIM_CARD']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                break;
                            
                            case Ticket::TYPE['MUSICAL_SHOW']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                break;

                            case Ticket::TYPE['CITY_EXPLORE_PASS']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                break;
                            
                        }

                    }  else {
                        switch ($ticket->ticket_type) {
                            case Ticket::TYPE['REGULAR']:
                                $old_sub_item = ReservationSubItem::find($sub_item['id']);
                                if(($old_sub_item['rq_schedule_datetime'] == null || $old_sub_item['rq_schedule_datetime'] == "" ) && $old_sub_item['rq_schedule_datetime'] !== $sub_item['rq_schedule_datetime']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                    $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                    $old_sub_item->update(['ticket_sent_status' => $item['sub_items'][$index]['ticket_sent_status'], 'ticket_sent_date' => $item['sub_items'][$index]['ticket_sent_date']]);
                                
                                } else if($old_sub_item['rq_schedule_datetime'] !== null && $old_sub_item['ticket_sent_status'] !== ReservationSubItem::SEND_STATUS['SENT']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                    $old_sub_item->update(['ticket_sent_status' => $item['sub_items'][$index]['ticket_sent_status']]);
                                }
                                else {
                                    $item['sub_items'][$index]['ticket_sent_status'] = $old_sub_item['ticket_sent_status'];
                                }

                                if($old_sub_item['ticket_sent_status'] == ReservationSubItem::SEND_STATUS['TBD'] && $old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['REFUNDED']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['REFUNDED'];
                                    $old_sub_item->update(['ticket_sent_status' => $item['sub_items'][$index]['ticket_sent_status']]);
                                } else if ($old_sub_item['ticket_sent_status'] == ReservationSubItem::SEND_STATUS['SENT'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['REFUNDED'] ){
                                    $message = 'Item ID: '.$item['id'].'. The ticket "'.$ticket->title_en.'" cannot be placed as "'.$item['sub_items'][$index]['refund_status'].'" because it has a send status of "Sent".';
                                    throw new \Exception($message);
                                } else if ($old_sub_item['ticket_sent_status'] == ReservationSubItem::SEND_STATUS['SENT'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['PICKED_UP']){
                                    $message = 'Item ID: '.$item['id'].'. The ticket "'.$ticket->title_en.'" cannot be placed as "'.$item['sub_items'][$index]['refund_status'].'" because it has a send status of "Sent".';
                                    throw new \Exception($message);
                                }
                                else if ($old_sub_item['ticket_sent_status'] == ReservationSubItem::SEND_STATUS['SENT'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['IN_PROGRESS']){
                                    $message = 'Item ID: '.$item['id'].'. The ticket "'.$ticket->title_en.'" cannot be placed as "'.$item['sub_items'][$index]['refund_status'].'" because it has a send status of "Sent".';
                                    throw new \Exception($message);
                                }

                                break;
    
                            case Ticket::TYPE['BAR_QR']:
                                $old_sub_item = ReservationSubItem::find($sub_item['id']);
                                if($old_sub_item['ticket_sent_status'] == ReservationSubItem::SEND_STATUS['SENT'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['REFUNDED']){
                                    $message = 'Item ID: '.$item['id'].'. The ticket "'.$ticket->title_en.'" cannot be placed as "'.$item['sub_items'][$index]['refund_status'].'" because it has a send status of "Sent".';
                                    throw new \Exception($message);
                                }
                                if($old_sub_item['ticket_sent_status'] !== ReservationSubItem::SEND_STATUS['SENT'] && $old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['REFUNDED']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['REFUNDED'];
                                } else {
                                    $item['sub_items'][$index]['ticket_sent_status'] = $old_sub_item['ticket_sent_status'];
                                }
                                break;
                            case Ticket::TYPE['GUIDE_TOUR']:
                                $old_sub_item = ReservationSubItem::find($sub_item['id']);
                                if($old_sub_item['ticket_sent_status'] == ReservationSubItem::SEND_STATUS['SENT'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['REFUNDED']){
                                    $message = 'Item ID: '.$item['id'].'. The ticket "'.$ticket->title_en.'" cannot be placed as "'.$item['sub_items'][$index]['refund_status'].'" because it has a send status of "Sent".';
                                    throw new \Exception($message);
                                }
                                if($old_sub_item['rq_schedule_datetime'] !== $sub_item['rq_schedule_datetime']){
                                    if($sub_item['rq_schedule_datetime'] !== null){
                                        $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                        $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                    } else {
                                        $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                    }
                                } else {
                                    $item['sub_items'][$index]['ticket_sent_status'] = $old_sub_item['ticket_sent_status'];
                                }
                                
                                break;
                            case Ticket::TYPE['HARD_COPY']:
                                $old_sub_item = ReservationSubItem::find($sub_item['id']);
                                if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['PICKED_UP']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['PICKED_UP'];
                                    $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } else if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['REFUNDED']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['REFUNDED'];
                                    $item['sub_items'][$index]['refund_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } else if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == null){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                }else if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['IN_PROGRESS']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                } else {
                                    $item['sub_items'][$index]['ticket_sent_status'] = $old_sub_item['ticket_sent_status'];
                                }
                                break;

                            case Ticket::TYPE['SIM_CARD']:
                                $old_sub_item = ReservationSubItem::find($sub_item['id']);
                                if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['PICKED_UP']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['PICKED_UP'];
                                    $item['sub_items'][$index]['ticket_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } else if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['REFUNDED']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['REFUNDED'];
                                    $item['sub_items'][$index]['refund_sent_date'] = Carbon::now()->format('Y-m-d H:i:s');
                                } else if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == null){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                }else if($old_sub_item['refund_status'] !== $sub_item['refund_status'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['IN_PROGRESS']){
                                    $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['OFFICE_PICKUP'];
                                }else {
                                    $item['sub_items'][$index]['ticket_sent_status'] = $old_sub_item['ticket_sent_status'];
                                }
                                break;
                            
                            case Ticket::TYPE['MUSICAL_SHOW']:
                                if($old_sub_item['ticket_sent_status'] == ReservationSubItem::SEND_STATUS['SENT'] && $sub_item['refund_status'] == Reservation::TICKET_REFUNDED_STATUS['REFUNDED']){
                                    $message = 'Item ID: '.$item['id'].'. The ticket "'.$ticket->title_en.'" cannot be placed as "'.$item['sub_items'][$index]['refund_status'].'" because it has a send status of "Sent".';
                                    throw new \Exception($message);
                                }
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                break;

                            case Ticket::TYPE['CITY_EXPLORE_PASS']:
                                $old_sub_item = ReservationSubItem::find($sub_item['id']);
                                if($item['sub_items'][$index]['ticket_sent_status'] == null){
                                    $item['sub_items'][$index]['ticket_sent_status'] = $old_sub_item['ticket_sent_status'];
                                }
                                break;
                        }
                    }

                    if($item['price_list_id']){
                        if ($ticket->additional_price_type == 'Premium'){
                        $item['sub_items'][$index]['addition'] = $ticket->premium_amount;

                        } else if ($ticket->additional_price_type == 'Premium S'){
                        $item['sub_items'][$index]['addition'] = $ticket->premium_s_amount;
                        
                        }
                    } else {
                        $item['sub_items'][$index]['addition'] = 0;
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
                
                $validator = Validator::make($data,[
                    'payment_type' =>['nullable'],
                    'credit' => ['required_if:payment_type,Cash'],
                    'stripe_token' => ['required_if:payment_type,Credit Card,null'],
                ]);

                if( $validator->fails()){
                    throw new StripeTokenFailException($validator->messages());
                }
                
                $data = $validator->validate();

                $data['total'] = $reservation_old->total - $total_old;             
                
                $reservation_old->status = Reservation::STATUS['NO_PAID'];

                if($data['payment_type'] == Reservation::PAYMENT_TYPE['CASH']){
                    $response = ServiceCashPayment::create($reservation_old, $data);
                }  else{
                    $response = ServiceCreditCard::create($reservation_old, $data);
                }
                
                if (array_key_exists('original', $response) && array_key_exists('errors', $response->original)) {
                    throw new \Exception($response);
                } 

                $template = Template::where('title','After Upgraded Order')->first();

                if($template->subject == 'default'){
                    $subject = '[타마스] Order Upgraded: # '.$reservation_old->order_number.' '.$reservation_old->customer_name_kr." Payment Completed";
                } else {
                    $subject = '[타마스] Order Upgraded: # '.$reservation_old->order_number.' '.$reservation_old->customer_name_kr." ".$template->subject;
                }


                Mail::send('email.upgradedOrder', ['fullname' => $reservation_old->customer_name_kr, 'amount'=> $data['total'], 'template' => $template], function($message) use($reservation_old, $template, $subject, $data){
                    $message->to($reservation_old->email);
                    $message->subject($subject);
                    $fullname = $reservation_old->customer_name_kr;
                    $orderNumber = $reservation_old->order_number;
                    $email_customer = $reservation_old->email;
                    $orderDate = $reservation_old->created_at->format('Y-m-d g:i A');
                    $discount = $reservation_old->discount_amount;
                    $amount = $data['total'];
                    $iconDashboardSquare = public_path('images/dashboard-square.svg');
                    $iconBookOpen = public_path('images/book-open.svg');
                    $iconDollarCircle = public_path('images/dollar-circle.svg');
                    $iconMessage = public_path('images/message.svg');
                    $iconLocation = public_path('images/location.svg');
                    $reservationItems = $reservation_old->reservationItems()->with('reservationSubItems.ticket:id,title_kr,ticket_type', 'subcategory:id,name', 'category:id,name','priceList:id,product_type')->get();
                    
                    $user_signed = User::where('email',$reservation_old->email)->first();
                    $name_customer =$user_signed ? $user_signed->firstname.' '.$user_signed->lastname : $reservation_old->customer_name_kr;
                    $cash_type =false; 
                    $credit_type =false; 

                    if($data['payment_type'] == Reservation::PAYMENT_TYPE['CASH']){
                        $bill_data = $reservation_old->reservationCashPayments()->get()->last();
                        $cash_type =true; 
                    } else {
                        $bill_data = $reservation_old->reservationCreditCardPayments()->get()->last();
                        $credit_type =true; 
                    }

                    $auth = false;

                    $pdf = PDF2::loadView('invoicePayment', compact('iconDashboardSquare','iconBookOpen','iconDollarCircle','iconMessage','iconLocation','fullname','amount', 'orderNumber','orderDate','reservationItems','discount','cash_type','credit_type','bill_data', 'auth','name_customer','email_customer'));

                    $message->attachData($pdf->output(), 'Tamice-Receipt.pdf');
                });
            }
            
          return $reservation_old->load('reservationItems.reservationSubItems');
	}

    public static function response($reservation)
    {
        return $reservation;
    }
}