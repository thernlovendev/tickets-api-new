<?php

namespace App\Services\Cities;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;

        $mapCollection = $data_map->map( function($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'status' => $item->status,
                'company' => $item->company->name
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
        if(!isset($filters['sort'])){
            $models->orderBy('created_at','DESC');
        }
        
        return $models;
    }

}