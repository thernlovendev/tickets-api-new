<?php

namespace App\Imports;

use App\Models\TicketStock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TicketStocksImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return TicketStock|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            User::create([
                'name' => $row[0],
            ]);
        }
    }
}