<?php

namespace App\Services\Inventories;
use App\Models\TicketStock;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;

        $mapCollection = $data_map->map( function($item){
            $alert = $item->range_age_type == 'Adult' ? $item->out_of_stock_alert_adult : $item->out_of_stock_alert_child;

            $item['stock_req'] = $item->total_valid < $alert ? 'Place Order' : 'Sufficient';    
            return $item;
        });

        if(isset($request->per_page)){
            $data['data'] = $mapCollection;
        } else {
            $data = $mapCollection;
        }
        return $data;
    }

    public static function filterCustom($filters, $models){

        if(isset($filters['title_en'])){
            $models->whereHas('ticket', function($query) use($filters){
                $query->where('title_en', 'LIKE', '%'.$filters['title_en'].'%');
            });
        }
        return $models;
    }

}