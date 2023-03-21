<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = Company::pluck('id');

        foreach ($companies as $company) {
            City::updateOrCreate(['name' => 'New York', 'company_id' =>  $company], ['name' => 'New York', 'company_id' =>  $company]); 
            City::updateOrCreate(['name' => 'San Fransisco', 'company_id' =>  $company], ['name' => 'San Fransisco', 'company_id' =>  $company]);
        }
    }
}
