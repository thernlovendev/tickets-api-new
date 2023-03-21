<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::get();

        foreach ($categories as $category) {
            Subcategory::updateOrCreate(['category_id'=> $category->id, 'name' => 'Sub Category'.$category->id],['category_id'=> $category->id,'name' => 'Sub Category'.$category->id]); 
        }
        
    }
}
