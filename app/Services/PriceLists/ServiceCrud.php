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

             //condicion para eliminar los que no esta en la nueva data
            $price_list_saved = PriceList::where('category_id', $data['category_id'])
             ->pluck('id');

             $price_list_data = collect($data['prices'])->whereNotNull('id')->pluck('id');

            $price_to_delete = $price_list_saved->diff($price_list_data)->all();

            if(isset($price_to_delete)){
                PriceList::whereIn('id', $price_to_delete)->delete();
            }

            foreach ($data['prices'] as $price) {
                
                if(isset($price['id'])){
                    PriceList::find($price['id'])->update([
                        'subcategory_id' => $price['subcategory_id'],
                        'product_type' => $price['product_type'],
                        'adult_price' => $price['adult_price'], 
                        'child_price' => $price['child_price'],
                        'quantity' => $price['quantity']],
                    );

                    //actualiza
                } else {
                    //crea
                    PriceList::create([
                        'category_id' => $data['category_id'],
                        'subcategory_id' => $price['subcategory_id'],
                        'product_type' => $price['product_type'],
                        'adult_price' => $price['adult_price'], 
                        'child_price' => $price['child_price'],
                        'quantity' => $price['quantity']],
                    );
                }
            
                // $item = PriceList::create([
                //     'category_id'=> $data->category_id,
                //     'subcategory_id'=> $price['subcategory_id'],
                //     'product_type' => $price['product_type'],
                //     'adult_price' => $price['adult_price'], 
                //     'child_price' => $price['child_price'],
                //     'quantity' => $price['quantity']]
                // ); 
                
                // $prices[] = $item;
            }
            

            DB::commit();

            return $data;

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

	public static function update($data, $reservation)
	{
		try{
            DB::beginTransaction();

            //  //condicion para eliminar los que no esta en la nueva data
            //  $price_list_saved = PriceList::where('category_id', $data['category_id'])
            //  ->get();

            // $price_to_delete = $price_list_saved->diff($items);

            // dd($price_to_delete);

            
            // foreach ($data['prices'] as $price) {
                
            //     if(isset($price['id'])){
            //         //actualiza
            //     } else {
            //         //crea
            //     }
            // }

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