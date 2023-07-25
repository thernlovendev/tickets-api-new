<?php

namespace App\Services\Categories;
use App\Models\Category;
use App\Models\Subcategory;
use DB;
use Validator;
use App\Utils\ModelCrud;
use Illuminate\Validation\Rule;

class ServiceCrud
{
	public static function create($data)
	{
		try {
            DB::beginTransaction();

            $data_category = $data->validated();
            $category = Category::create(['city_id' => $data->city_id, 'name'=> $data_category['name']]);
            $count_subcategories = $data->subcategories;
            $subcategory_counter = [];
            if(!empty($count_subcategories)){
                foreach ($data->subcategories as $subcategory) {
                    $item = Subcategory::create(['category_id'=> $category->id,'name' => $subcategory['name']]); 
                    
                    $subcategory_counter[] = $item;
                }
            }

            $subcategories['Subcategory'] = $subcategory_counter;
			
            DB::commit();

            return [$category, $subcategories];

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

	public static function update($data, $category)
	{
		try{
            DB::beginTransaction();

            $category->update([
                'name' => $data['name']
            ]); 

            ModelCrud::deleteUpdateOrCreate($category->subcategories(), $data['subcategories']);

            DB::commit();
            return $data;

        } catch (\Exception $e){
            DB::rollback();
            return $e;
        }
	}

	public static function delete($category)
	{
        // $category->delete();
        // return $category;
    }

    public static function response($category)
    {
        return $category;
    }
}