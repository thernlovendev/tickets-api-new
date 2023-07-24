<?php

namespace App\Services\ProductSeats;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;

        $mapCollection = $data_map->map( function($item){

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
        
        if(isset($filters['product_id'])){
            $models->where('product_id','LIKE', '%'.$filters['product_id'].'%');
        }

        if(isset($filters['product_code'])){
            $models->where('product_code','LIKE', '%'.$filters['product_code'].'%');
        }

        return $models;
    }

}