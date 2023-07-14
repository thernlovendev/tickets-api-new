<?php

namespace App\Services\Users;
use Carbon\Carbon;
use App\Models\Company;

class ServiceGeneral
{
    public static function mapCollection($data){
        $request = request();
        $data_map = $request->per_page ? $data['data'] : $data;


        $mapCollection = $data_map->map( function($item){

            $rol = $item->roles->first();
            $date = Carbon::parse($item->created_at)->format('Y-m-d');
            $company = Company::where('id', $item->company_id)->pluck('name')->first();
            
            return [
                'id' => $item->id,
                'name' => $item->name,
                'email' => $item->email,
                'create_on' => $date,
                'last_login' => $item->last_login_at,
                'active' => $item->active,
                'email_status' => $item->email_verified_at,
                'password' =>  $item->password,
                'company_id'=> $item->company_id,
                'company_name' => $company
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

        if(isset($filters['role_name'])){
            $models->whereHas('roles', function($query) use ($filters){
                    return $query->where('name', $filters['role_name']);
                });
        }

        return $models;
    }

}