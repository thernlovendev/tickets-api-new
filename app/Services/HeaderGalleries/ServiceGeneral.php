<?php

namespace App\Services\HeaderGalleries;

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
        
        if(isset($filters['title'])){
            $models->where('title','LIKE', '%'.$filters['title'].'%');
        }

        if(isset($filters['first_phrase'])){
            $models->where('first_phrase','LIKE', '%'.$filters['first_phrase'].'%');
        }

        if(isset($filters['second_phrase'])){
            $models->where('second_phrase','LIKE', '%'.$filters['second_phrase'].'%');
        }
        return $models;
    }

}