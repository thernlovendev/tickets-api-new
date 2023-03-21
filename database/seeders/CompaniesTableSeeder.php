<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::updateOrCreate(['name' => 'Tamice'], ['name' => 'Tamice']); 
        Company::updateOrCreate(['name' => 'Hinewyorking'], ['name' => 'Hinewyorking']); 
    }
}
