<?php

namespace App\Http\Controllers\API;

use App\Services\Tickets\ServiceCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;

class TicketsController extends Controller
{
    public function store(TicketRequest $request)
    {
        $ticket = ServiceCrud::create($request);
        return Response($ticket, 200);

    }
}
