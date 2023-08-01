<?php

namespace App\Services\Reservations;
use App\Models\User;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;

        $mapCollection = $data_map->map( function($item){

            $customer = User::where('email', $item->email)->first();

            return [
                'id' => $item->id,
                'created_by' => $item->created_by,
                'departure_date' => $item->departure_date,
                'order_date' => $item->order_date,
                'order_number' => $item->order_number,
                'customer' =>$customer ? $customer->name : $item->customer_name_en,
                'payment_type' => $item->payment_type,
                'ticket_sent_status' => $item->ticket_sent_status,
                'status' => $item->status,
                'phone' => $item->phone,
                'email' => $item->email,
                'reservation_items' => $item->reservationItems,
                'vendor_comissions' => $item->vendorComissions
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
        if(isset($filters['customer'])){
            $models->where('customer_name_en','LIKE', '%'.$filters['customer'].'%')
                    ->orWhere('customer_name_kr','LIKE', '%'.$filters['customer'].'%');
        }

        if(isset($filters['email'])){
            $models->where('email','LIKE', '%'.$filters['email'].'%');
        }

        if(isset($filters['phone'])){
            $models->where('phone','LIKE', '%'.$filters['phone'].'%');
        }

        if(isset($filters['username'])){
            $models->where('created_by','LIKE', '%'.$filters['username'].'%');
        }

        if(isset($filters['ids_filter'])){
            $models->whereIn('id', $filters['ids_filter']);
        }

        return $models;
    }

}