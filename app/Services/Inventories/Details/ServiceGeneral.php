<?php

namespace App\Services\Inventories\Details;
use App\Models\TicketStock;
use App\Models\Reservation;
use App\Models\Ticket;
use App\Models\ReservationSubItem;
use Carbon\Carbon;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;

        $mapCollection = $data_map->map( function($item){
            $ticket = Ticket::withTrashed()->find($item->ticket_id);
            $used = count($item->stocksUsed);

            $customer_name = null;
            $ticket_code = null;
            $item_number = null;
            $order_id = null;
            $reservation_id = null;
            $sub_item_id = null;
            

            if($used > 0){
                $stock = $item->stocksUsed->first();
                $reservation = Reservation::withTrashed()->find($stock->reservation_id);
                $sub_item = ReservationSubItem::withTrashed()->find($stock->reservation_sub_item_id);

                $item_number = $sub_item ? $sub_item->reservation_item_id : null;
                $customer_name = $reservation->customer_name_en;
                $order_id = $reservation->order_number;
                $reservation_id = $reservation->id;
                $sub_item_id = $sub_item ? $sub_item->id : null;


            }

            return [
                'id' => $item->id,
                'code_number' => $item->code_number,
                'customer_name' => $customer_name,
                'ticket_code' => $ticket->product_code,
                'order_id' => $order_id,
                'item_number' => $item_number,
                'expiration_date' => $item->expiration_date,
                'status' => $item->status,
                'uploaded_date' => Carbon::parse($item->created_at)->format('Y-m-d'),
                'reservation_id' => $reservation_id,
                'sub_item_id' => $sub_item_id,
                'type' => $item->type,
                'ticket_title_en' => $ticket->title_en
            ];
        });

        if(isset($request->per_page)){
            $data['data'] = $mapCollection;
        } else {
            $data = $mapCollection;
        }

        return $data;
    }

    public static function filterCustom($filters, $models){

       
        return $models;
    }

}