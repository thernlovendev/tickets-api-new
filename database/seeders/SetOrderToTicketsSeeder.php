<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\Company;


/* This seeder is only for initialized the order in tickets data if exist */

class SetOrderToTicketsSeeder extends Seeder
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
            $tickets = Ticket::where('company_id', $company)->orderBy('created_at')->get();

            foreach ($tickets as $key => $ticket) {
                $ticket->update(['order' => $key + 1]);
                echo 'updating ticket '.$ticket->id.'...'.PHP_EOL;
            }
        }
        echo 'Done';
    }
}
