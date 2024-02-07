<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReservationExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Reservation::select('customer_name_en','customer_name_kr','created_by','created_at')->get();
    }

    public function headings(): array
    {
        return ["customer_name_en","customer_name_kr","created_by","created_at"];
    }
}
