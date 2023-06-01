<?php

namespace App\Services\Tickets;

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
        
        if(isset($filters['title_en'])){
            $models->where('title_en','LIKE', '%'.$filters['title_en'].'%');
        }

        if(isset($filters['product_code'])){
            $models->where('product_code','LIKE', '%'.$filters['product_code'].'%');
        }

        if(isset($filters['category'])){
            $category_id = $filters['category'];
            $models->whereHas('categories', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });
        }

        if(isset($filters['sub_category'])){
            $sub_category = $filters['sub_category'];
            $models->whereHas('subcategories', function ($query) use ($sub_category) {
                $query->where('subcategory_id', $sub_category);
            });
        }
        return $models;
    }

}