<?php

namespace App\Services\Reservations;
use App\Models\User;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;

        $mapCollection = $data_map->map( function($item){

            $customer = User::where('email', $item->email)->first();

            return [
                'id' => $item->id,
                'created_by' => $item->created_by,
                'departure_date' => $item->departure_date,
                'order_date' => $item->created_at->format('Y-m-d g:i A'),
                'order_number' => $item->order_number,
                'customer_name_en' => $item->customer_name_en,
                'customer_name_kr' => $item->customer_name_kr,
                'phone' => $item->phone,
                'customer' =>$customer ? $customer->name : $item->customer_name_en,
                'payment_type' => $item->payment_type,
                'ticket_sent_status' => $item->ticket_sent_status,
                'status' => $item->status,
                'email' => $item->email,
                'total' => $item->total,
                'reservation_items' => $item->reservationItems,
                'vendor_comissions' => $item->vendorComissions,
                'user' => $customer ? $customer : $item->customer_name_en
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
        
        if(isset($filters['customer'])){
            $models->where('customer_name_en','LIKE', '%'.$filters['customer'].'%')
                    ->orWhere('customer_name_kr','LIKE', '%'.$filters['customer'].'%');
        }

        if(isset($filters['email'])){
            $models->where('email','LIKE', '%'.$filters['email'].'%');
        }

        if(isset($filters['phone'])){
            $models->where('phone','LIKE', '%'.$filters['phone'].'%');
        }

        if(isset($filters['username'])){
            $models->where('created_by','LIKE', '%'.$filters['username'].'%');
        }

        if(isset($filters['ids_filter'])){
            $models->whereIn('id', $filters['ids_filter']);
        }

        if(isset($filters['order_number'])){
            $models->where('order_number','LIKE', '%'.$filters['order_number'].'%');
        }

        if(isset($filters['sent_status'])){

            if($filters['sent_status'] == 'Sent'){
                // $models->whereHas('reservationItems', function ($query) use ($filters) {
                //     $query->whereDoesntHave('reservationSubItems', function ($q) use ($filters) {
                //         // Asegúrate de que ningún subelemento esté en estado diferente a 'Sent'
                //         $q->where('ticket_sent_status', 'NOT LIKE', '%' . $filters['sent_status'] . '%');
                //     });
                // });

                // $models->whereHas('reservationItems', function ($query) {
                //     // Utiliza una subconsulta para verificar que todos los reservationSubItems tengan ticket_sent_status igual a 'Sent'
                //     $query->whereDoesntHave('reservationSubItems', function ($subQuery) {
                //         $subQuery->where('ticket_sent_status', '!=', 'Sent');
                //     });
                // });

                // dd($models);

                // $models->whereDoesntHave('reservationItems', function ($query) use ($filters) {
                //     $query->whereHas('reservationSubItems', function ($subQuery) use ($filters) {
                //         $subQuery->where('ticket_sent_status', '!=', $filters['sent_status']);
                //     });
                // });
                $models->whereHas('reservationItems.reservationSubItems')->whereDoesntHave('reservationItems.reservationSubItems', function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery->where('ticket_sent_status', '!=', 'Sent')
                                 ->orWhereNull('ticket_sent_status');
                    });
                });

            } else {
                $models->whereHas('reservationItems', function($query) use($filters){
                    $query->whereHas('reservationSubItems', function($q) use($filters){
                        $q->where('ticket_sent_status', 'LIKE', '%'.$filters['sent_status'].'%');
                    });
                });

            }
        }

        return $models;
    }

}