<?php

namespace App\Services\Cities;
use App\Models\City;
use DB;
class ServiceCrud
{
	public static function create($data)
	{
		try {
            DB::beginTransaction();

            $data_cities = $data->validated();
            
            $cities_counter = [];
            foreach ($data->cities as $city) {
                $item = City::updateOrCreate(['company_id' => $data->company_id, 'name' => $city['name']], ['company_id' => $data->company_id, 'name' => $city['name'], 'status' => City::STATUS['PUBLISH']]);
                $cities_counter[] = $item;
            }
			
            $cities['Cities'] = $cities_counter;
            DB::commit();

            return Response($cities, 200);

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

	public static function update($data, $city)
	{
		try{
            DB::beginTransaction();

            $city->update([
                'name' => $data['name']
            ]); 

            DB::commit();
            return $data;

        } catch (\Exception $e){
            DB::rollback();
            return $e;
        }
	}

	public static function delete($city)
	{
        // $city->delete();
        // return $city;
    }

    public static function response($city)
    {
        return $city;
    }
}