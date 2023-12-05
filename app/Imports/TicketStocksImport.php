<?php

namespace App\Imports;

use App\Models\TicketStock;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TicketStocksImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return TicketStock|null
     */
    public function collection(Collection $rows)
    {
        // Filtra los registros que contienen al menos una celda null
        $filteredRows = $rows->reject(function ($row) {
            return in_array(null, $row->toArray(), true);
        });
    
        Validator::make($filteredRows->toArray(), [
            '*.0' => ['required','distinct',Rule::unique('ticket_stocks','code_number')],
        ])->validate();
        
        foreach ($filteredRows as $row) 
        {

            TicketStock::create([
                'code_number' => $row[0],
                'type' => $this->data['type'],
                'expiration_date' => $this->data['expiration_date'],
                'status' => TicketStock::STATUS['VALID'],
                'range_age_type' => $this->data['range_age_type'],
                'ticket_id' => $this->data['ticket_id'],
            ]);
        }
    }

    public function  __construct($data)
    {
        $this->data= $data;
    }
}