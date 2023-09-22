<?php

namespace App\Services\Reservations\CreateByUser;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\Ticket;
use App\Models\TicketStock;
use App\Models\Template;
use App\Models\Subcategory;
use App\Models\PriceList;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;
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
use Mail;
use App\Mail\InvoiceEmail;

class ServiceCrud
{
	public static function create($data)
	{
            do {
                $order_number =  mt_rand(1000000, 9999999);
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
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                break;

                            case Ticket::TYPE['BAR_QR']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TO_DO'];
                                
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

                                if(count($stocks) < $quantity){
                                    $message = 'The inventory of ticket "'.$ticket->title_en.'", of type "'.$range_age.'", has been exceeded, the available quantity is '.count($stocks);
                                    throw new \Exception($message);
                                }
                                break;
                            case Ticket::TYPE['GUIDE_TOUR']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                
                                break;
                            case Ticket::TYPE['HARD_COPY']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                break;
                            
                           }
                            if ($ticket->additional_price_type == 'Premium'){
                            $item['sub_items'][$index]['addition'] = $ticket->premium_amount;

                           } else if ($ticket->additional_price_type == 'Premium S'){
                            $item['sub_items'][$index]['addition'] = $ticket->premium_s_amount;
                            
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
                    $subject = '[타마스] Order Confirmation: # '.$reservation->order_number.' '.$reservation->customer_name_en." Payment Completed";
                } else {
                    $subject = '[타마스] Order Confirmation: # '.$reservation->order_number.' '.$reservation->customer_name_en." ".$template->subject;
                }
               
                // Mail::send('email.paymentCompleted', ['fullname' => $reservation->customer_name_en, 'amount'=> $data['total'], 'template' => $template], function($message) use($reservation, $template, $subject, $data){
                //     $message->to($reservation->email);
                //     $message->subject($subject);
                //     $fullname = $reservation->customer_name_en;
                //     $orderNumber = $reservation->order_number;
                //     $orderDate = $reservation->created_at->format('Y-m-d g:i A');
                //     $amount = $data['total'];
                //     $iconDashboardSquare = public_path('images/dashboard-square.svg');
                //     $iconBookOpen = public_path('images/book-open.svg');
                //     $iconDollarCircle = public_path('images/dollar-circle.svg');
                //     $iconMessage = public_path('images/message.svg');
                //     $iconLocation = public_path('images/location.svg');
                //     $reservationItems = $reservation->reservationItems()->with('reservationSubItems.ticket:id,title_en', 'subcategory:id,name', 'category:id,name')->get();


                //     $pdf = PDF::loadView('invoicePayment', compact('iconDashboardSquare','iconBookOpen','iconDollarCircle','iconMessage','iconLocation','fullname','amount', 'orderNumber','orderDate','reservationItems'));

                //     $message->attachData($pdf->output(), 'archivo.pdf');
                // });


            return $reservation->load(['reservationItems.reservationSubItems','vendorComissions']);

	}

    public static function update($data, $reservation_old)
	{
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

                    if(!isset($sub_item['id'])){
                        switch ($ticket->ticket_type) {
                            case Ticket::TYPE['REGULAR']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                break;
    
                            case Ticket::TYPE['BAR_QR']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TO_DO'];
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

                                if(count($stocks) < $quantity){
                                    $message = 'The inventory of ticket "'.$ticket->title_en.'", of type "'.$range_age.'", has been exceeded, the available quantity is '.count($stocks);
                                    throw new \Exception($message);
                                }
                                
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
                    
                    if ($ticket->additional_price_type == 'Premium'){
                    $item['sub_items'][$index]['addition'] = $ticket->premium_amount;

                    } else if ($ticket->additional_price_type == 'Premium S'){
                    $item['sub_items'][$index]['addition'] = $ticket->premium_s_amount;
                    
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
                    'stripe_token' => ['required'],
                ]);
                if( $validator->fails()){
                    throw new StripeTokenFailException($validator->messages());
                }
                
                $data = $validator->validate();

                $data['total'] = $reservation_old->total - $total_old;             
                    
                $reservation_old->status = Reservation::STATUS['NO_PAID'];
                
                $response = ServiceCreditCard::create($reservation_old, $data);
                
                if (array_key_exists('original', $response) && array_key_exists('errors', $response->original)) {
                    throw new \Exception($response);
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
            
          return $reservation_old->load('reservationItems.reservationSubItems');
	}

    public static function response($reservation)
    {
        return $reservation;
    }
}