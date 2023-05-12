<?php

namespace App\Services\Inventories;
use App\Models\TicketStock;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;

        $mapCollection = $data_map->groupBy('ticket_id','range_age_type')->map( function($item){
                return [
                    'id' => $item,
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