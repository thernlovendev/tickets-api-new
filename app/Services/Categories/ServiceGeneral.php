<?php

namespace App\Services\Categories;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;

        $mapCollection = $data_map->map( function($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'city_id' => $item->city_id,
                'subcategories' => $item->subcategories,
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