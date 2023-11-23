<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategory;

class AllowPremiumPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subcategory::update(['name' => '뉴욕빅애플패스'], ['allow_premium_prices' => true]);
        Subcategory::update(['name' => '샌프란시스코 빅애플패스'], ['allow_premium_prices' => true]);
    }
}
