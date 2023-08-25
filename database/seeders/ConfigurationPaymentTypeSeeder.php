<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationPaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configuration::updateOrCreate(['key' => 'PAYMENT_TYPE'], ['key' => 'PAYMENT_TYPE', 'value' => 'STRIPE']); 
    }
}
