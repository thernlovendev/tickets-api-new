<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ConfigurationPaymentTypeSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(NYCategoriesTableSeeder::class);
        $this->call(SFCategoriesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(EmailTemplateTableSeeder::class);
    }
}
