<?php

namespace App\Services\PriceLists;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\PriceList;

class ServiceCrud
{
	public static function create($data)
	{
		try {
            DB::beginTransaction();

            $prices = [];
            foreach ($data->prices as $price) {
                $item = PriceList::create([
                    'category_id'=> $data->category_id,'subcategory_id'=> $data->subcategory_id, 'product_type' => $price['product_type'], 'adult_price' => $price['adult_price'], 'child_price' => $price['child_price'],
                    'quantity' => $price['quantity']]
                ); 
                
                $prices[] = $item;
            }
            

            DB::commit();

            return $prices;

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

	public static function update($data, $reservation)
	{
		try{
            DB::beginTransaction();

            // $reservation->update([
            //     'name' => $data['name']
            // ]); 

            // ModelCrud::deleteUpdateOrCreate($reservation->subcategories(), $data['subcategories']);

            DB::commit();
            return $data;

        } catch (\Exception $e){
            DB::rollback();
            return $e;
        }
	}

	public static function delete($reservation)
	{
        // $reservation->delete();
        // return $reservation;
    }

    public static function response($reservation)
    {
        return $reservation;
    }
}