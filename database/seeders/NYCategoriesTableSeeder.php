<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\City;
use App\Models\Subcategory;

class NYCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = City::where('name','New York')->get();
        if ($cities->isNotEmpty()) {
            foreach ($cities as $city) {
                $category_package = Category::updateOrCreate(['city_id'=> $city->id, 'name'=> 'NY Package Tour'],['city_id'=> $city->id, 'name'=>'NY Package Tour']); 

                Subcategory::updateOrCreate(['category_id'=> $category_package->id, 'name'=> 'NY Big Apple Pass'],['category_id'=> $category_package->id, 'name'=>'NY Big Apple Pass']);
                Subcategory::updateOrCreate(['category_id'=> $category_package->id, 'name'=> 'NY City Pass'],['category_id'=> $category_package->id, 'name'=>'NY City Pass']);
                Subcategory::updateOrCreate(['category_id'=> $category_package->id, 'name'=> 'NY City Explore Pass'],['category_id'=> $category_package->id, 'name'=>'NY City Explore Pass']);

                $category_attractions = Category::updateOrCreate(['city_id'=> $city->id, 'name'=> 'NY City Attractions'],['city_id'=> $city->id, 'name'=>'NY City Attractions']); 
                Subcategory::updateOrCreate(['category_id'=> $category_attractions->id, 'name'=> 'NY Observation(Scenics)'],['category_id'=> $category_attractions->id, 'name'=>'NY Observation(Scenics)']);
                Subcategory::updateOrCreate(['category_id'=> $category_attractions->id, 'name'=> 'NY Museum/Gallery'],['category_id'=> $category_attractions->id, 'name'=>'NY Museum/Gallery']);
                Subcategory::updateOrCreate(['category_id'=> $category_attractions->id, 'name'=> 'NY Rides/Cruises'],['category_id'=> $category_attractions->id, 'name'=>'NY Rides/Cruises']);
                Subcategory::updateOrCreate(['category_id'=> $category_attractions->id, 'name'=> 'NY Activities'],['category_id'=> $category_attractions->id, 'name'=>'NY Activities']);

                $category_guide = Category::updateOrCreate(['city_id'=> $city->id, 'name'=> 'NY Guide Tour'],['city_id'=> $city->id, 'name'=>'NY Guide Tour']);     
                $category_sim = Category::updateOrCreate(['city_id'=> $city->id, 'name'=> 'NY SIM Card'],['city_id'=> $city->id, 'name'=>'NY SIM Card']);    
            }
            
        }        
    }
}
