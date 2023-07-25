<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\City;
use App\Models\Subcategory;


class SFCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = City::where('name','San Francisco')->get();
        if ($cities->isNotEmpty()) {
            // La colección no está vacía, tiene al menos un usuario
            foreach ($cities as $city) {
                $category_package = Category::updateOrCreate(['city_id'=> $city->id, 'name'=> 'SF Package Tour'],['city_id'=> $city->id, 'name'=>'SF Package Tour']); 

                Subcategory::updateOrCreate(['category_id'=> $category_package->id, 'name'=> 'SF Big Apple Pass'],['category_id'=> $category_package->id, 'name'=>'SF Big Apple Pass']);
                Subcategory::updateOrCreate(['category_id'=> $category_package->id, 'name'=> 'SF City Pass'],['category_id'=> $category_package->id, 'name'=>'SF City Pass']);
                Subcategory::updateOrCreate(['category_id'=> $category_package->id, 'name'=> 'SF NY Explore Pass'],['category_id'=> $category_package->id, 'name'=>'SF NY Explore Pass']);

                $category_attractions = Category::updateOrCreate(['city_id'=> $city->id, 'name'=> 'SF City Attractions'],['city_id'=> $city->id, 'name'=>'SF City Attractions']); 
                Subcategory::updateOrCreate(['category_id'=> $category_attractions->id, 'name'=> 'SF Observation(Scenics)'],['category_id'=> $category_attractions->id, 'name'=>'SF Observation(Scenics)']);
                Subcategory::updateOrCreate(['category_id'=> $category_attractions->id, 'name'=> 'SF Museum/Gallery'],['category_id'=> $category_attractions->id, 'name'=>'SF Museum/Gallery']);
                Subcategory::updateOrCreate(['category_id'=> $category_attractions->id, 'name'=> 'SF Rides/Cruises'],['category_id'=> $category_attractions->id, 'name'=>'SF Rides/Cruises']);
                Subcategory::updateOrCreate(['category_id'=> $category_attractions->id, 'name'=> 'SF Activities'],['category_id'=> $category_attractions->id, 'name'=>'SF Activities']);

                $category_guide = Category::updateOrCreate(['city_id'=> $city->id, 'name'=> 'SF Guide Tour'],['city_id'=> $city->id, 'name'=>'SF Guide Tour']);     
                $category_sim = Category::updateOrCreate(['city_id'=> $city->id, 'name'=> 'SF SIM Card'],['city_id'=> $city->id, 'name'=>'SF SIM Card']);    
            }
            
        }        
    }
}
