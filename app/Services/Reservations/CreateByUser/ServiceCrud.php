<?php

namespace App\Services\Reservations\CreateByUser;
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
use Carbon\Carbon;

class ServiceCrud
{
	public static function create($data)
	{
            do {
                $order_number =  mt_rand(1000000, 9999999);
                settype($order_number, 'string');
            } while (Reservation::where("order_number", "=", $order_number)->exists());
            
            $data['order_number'] = $order_number;
            
            $created_by = Auth::user();
            if($created_by){
                $data['customer_name_en'] = $created_by->name;
                $data['customer_name_kr'] = $created_by->name;
            } else {
                $data['customer_name_en'] = $data['first_name'].' '.$data['last_name'];
                $data['customer_name_kr'] = $data['first_name'].' '.$data['last_name'];
            };
            $data['created_by'] = 'Customer';

            $data['order_date'] = Carbon::now()->format('Y-m-d');
            
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
               
                $response = ServiceCreditCard::create($reservation, $data);
            

            return $reservation->load(['reservationItems.reservationSubItems','vendorComissions']);

	}

    public static function response($reservation)
    {
        return $reservation;
    }
}