<?php

namespace App\Services\Tickets;
use Carbon\Carbon;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;

        $mapCollection = $data_map->map( function($item) use($request) {
            
            if($request->filled('stock_available')){
                $now = Carbon::now()->format('Y-m-d');
                $stocks_adult = $item->ticketStocks()->where('range_age_type', 'Adult')
                ->where('status', 'Valid')
                ->where('expiration_date', '>', $now)
                ->count();

                $stocks_child = $item->ticketStocks()->where('range_age_type', 'Child')
                ->where('status', 'Valid')
                ->where('expiration_date', '>', $now)
                ->count();

                $item['can_sell_adult'] = $item->out_of_stock_alert_adult > $stocks_adult ? false: true;
                $item['can_sell_child'] = $item->out_of_stock_alert_child > $stocks_child ? false: true;

            }

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

        if(isset($filters['order'])){
            $models->where('order', $filters['order']);
        }

        if(isset($filters['company_id'])){
            $models->where('company_id', $filters['company_id']);
        }

        if(isset($filters['ids_filter'])){
            $models->whereIn('id', $filters['ids_filter']);
        }

        return $models;
    }

}