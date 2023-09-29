<?php

namespace App\Services\PriceLists;

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
        //filer by razon social

        if(isset($filters['ids_filter'])){
            $models->whereIn('id', $filters['ids_filter']);
        }
        
        return $models;
    }

}