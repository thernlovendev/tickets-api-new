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
                'customer' => $customer ? $customer->name : $item->customer_name_en,
                'payment_type' => $item->payment_type,
                'ticket_sent_status' => $item->ticket_sent_status,
                'status' => $item->status
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