<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = City::pluck('id');;
         

        foreach ($cities as $city) {
            $category_package = Category::updateOrCreate(['city_id'=> $city, 'name'=> 'Package Tour'],['city_id'=> $city, 'name'=>'Package Tour']); 

            $category_guide = Category::updateOrCreate(['city_id'=> $city, 'name'=> 'Guide Tour'],['city_id'=> $city, 'name'=>'Guide Tour']); 
        
            
            $category_musical = Category::updateOrCreate(['city_id'=> $city, 'name'=> 'Musicals / Show'],['city_id'=> $city, 'name'=>'Musicals / Show']); 
        

            $category_gta = Category::updateOrCreate(['city_id'=> $city, 'name'=> 'GTA'],['city_id'=> $city, 'name'=>'GTA']);
        

        }

        
    }
}
