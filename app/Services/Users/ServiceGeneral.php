<?php

namespace App\Services\Users;
use Carbon\Carbon;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;


        
        $mapCollection = $data_map->map( function($item){
            $rol = $item->roles->first();
            $date = Carbon::parse($item->created_at)->format('Y-m-d');
            return [
                'id' => $item->id,
                'name' => $item->name,
                'email' => $item->email,
                'create_on' => $date,
                'last_login' => $item->last_login_at,
                'active' => $item->active,
                'email_status' => $item->email_verified_at,
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

        if(isset($filters['email'])){
            $models->where('email','LIKE', '%'.$filters['email'].'%');
        }

        if(isset($filters['name'])){
            $models->where('name','LIKE', '%'.$filters['name'].'%');
        }

        if(isset($filters['role_id'])){
            $models->whereHas('roles', function($query) use ($filters){
                    return $query->where('id', $filters['role_id']);
                });
        }

        return $models;
    }

}