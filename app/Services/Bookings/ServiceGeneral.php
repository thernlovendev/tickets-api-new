<?php

namespace App\Services\Bookings;
use App\Models\User;
use App\Models\Reservation;
use App\Models\ReservationItem;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;

        $mapCollection = $data_map->map( function($item){

            $customer = User::where('email', $item->email)->first();

            return [
                'id' => $item->id,
                'order_date' => $item->order_date,
                'order_number' => $item->order_number,
                'items' => $item->reservationItems,
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

        return $models;
    }

}