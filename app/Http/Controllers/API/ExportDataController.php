<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\TicketsExport;
use App\Exports\ReservationExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportDataController extends Controller
{
    public function ticketsExport(){
        return Excel::download(new TicketsExport, 'tickets.xlsx');
    }

    public function reservationsExport(){
        return Excel::download(new ReservationExport, 'reservations.xlsx');
    }
}
