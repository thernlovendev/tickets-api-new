<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::firstOrCreate([
            'name' => 'admin admin',
            'firstname' => 'admin',
            'lastname' => 'admin',
            'email' => 'admin@gmail.com',
            'phone' => '+54 123-4567',
        ],
        [
            'password' => bcrypt('123456')
        ]);
    
        $admin->assignRole('super admin');

        $admin->save();
    }
}
