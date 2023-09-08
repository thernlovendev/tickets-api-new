<?php

namespace App\Services\Inventories;
use App\Models\TicketStock;
use Carbon\Carbon;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();

        if(isset($request->per_page)){
            $response = $data['data'];
        } else {
            $response = $data;
        }

        $group = $response->groupBy(function ($item) {
            return $item['range_age_type'].'-'.$item['ticket_id'];
        })->map(function($group){
            $first_item = $group->first();
            $alert = $first_item['range_age_type'] == 'Adult' ? $first_item['out_of_stock_alert_adult'] : $first_item['out_of_stock_alert_child'];
            $today = Carbon::now()->format('Y-m-d');
            $total = TicketStock::where('ticket_id', $first_item->ticket_id)
            ->where('range_age_type', $first_item->range_age_type)
            ->where('status', TicketStock::STATUS['VALID'])
            ->whereDate('expiration_date', '>', $today)
            ->count();

            return [
                'ticket_id' => $first_item->ticket_id,
                'range_age_type' => $first_item->range_age_type,
                'total' => $total,
                'stock_req' => $total < $alert ? 'Place Order' : 'Sufficient',
                'stocks' => $group
            ];
        })->values();
        
        if(isset($request->per_page)){
            $data['data'] = $group;
        } else {
            $data = $group;
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