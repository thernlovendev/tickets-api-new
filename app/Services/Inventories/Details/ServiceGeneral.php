<?php

namespace App\Services\Inventories\Details;
use App\Models\TicketStock;
use Carbon\Carbon;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;

        $mapCollection = $data_map->map( function($item){
            return [
                'id' => $item->id,
                'code_number' => $item->code_number,
                'customer_name' => null,
                'ticket_code' => null,
                'order_id' => null,
                'item_number' => null,
                'expiration_date' => $item->expiration_date,
                'status' => $item->status,
                'uploaded_date' => Carbon::parse($item->created_at)->format('Y-m-d'),
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