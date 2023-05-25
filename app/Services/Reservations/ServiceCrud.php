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
use Illuminate\Support\Facades\Auth;
use App\Services\Reservations\ServiceCashPayment;
use App\Services\Reservations\ServiceCreditCard;
use App\Utils\ModelCrud;
use Illuminate\Validation\ValidationException;

class ServiceCrud
{
	public static function create($data)
	{
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
                //Setea el adicional desde el ticket y el estado
                
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
                            case Ticket::TYPE['TOUR_TICKET']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['SENT'];
                                
                                break;
                            case Ticket::TYPE['HARD_COPY']:
                                $item['sub_items'][$index]['ticket_sent_status'] = ReservationSubItem::SEND_STATUS['TBD'];
                                break;
                            
                        }

                    } 
                    
                    $item['sub_items'][$index]['addition'] = $ticket->additional_price_amount;
                }

                $addition = collect($item['sub_items'])->sum('addition');
                $total = ($item['price'] + $addition) * $item['quantity'];

                $item_model->update(['addition' => $addition, 'total' => $total]);
                
                ModelCrud::deleteUpdateOrCreate($item_model->reservationSubItems(), $item['sub_items']);
            }

            $reservation_old->subtotal = $reservation_old->reservationItems()->sum('total');
            
            $reservation_old->total = round($reservation_old->subtotal - $reservation_old->discount_amount, 2);

            $reservation_old->save();

            if($reservation_old->total - $total_old > 0){
               
                $validator = Validator::make($data, [
                    'payment_type' => ['required',Rule::in(Reservation::PAYMENT_TYPE)],
                    'credit' => ['required_if:payment_type,Cash'],
                    'token_stripe'=> 'required_if:payment_type,Credit Card'
                ]);

                if( $validator->fails() ){
                    // return $validator;
                    throw ValidationException::withMessages([
                        'errors' => $validator->errors()
                    ]);
                }

                $data = $validator->validate();

                $data['total'] = $reservation_old->total - $total_old;
                if($data['payment_type'] == Reservation::PAYMENT_TYPE['CASH']){
                    $response = ServiceCashPayment::create($reservation_old, $data);
                }  else{
                    $response = ServiceCreditCard::create($reservation_old, $data);
                }
            }
            
          return $reservation_old;
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