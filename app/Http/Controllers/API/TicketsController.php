<?php

namespace App\Http\Controllers\API;

use App\Services\Tickets\ServiceCrud;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;
use App\Services\Tickets\ServiceGeneral;

class TicketsController extends Controller
{

    public function index(Request $request)
    {
        $tickets = Ticket::with(['cardImage', 'ticketPrices']);
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $tickets);
        $elements = $this->httpIndex($elements, ['id', 'status', 'product_code']);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
    }

    public function store(TicketRequest $request)
    {
        $ticket = ServiceCrud::create($request);
        return Response($ticket, 201);
    }

    public function show(Ticket $ticket)
    {
        $response = $ticket->load(['categories', 'subcategories', 'ticketPrices', 'ticketContents', 'ticketSchedules', 'wideImages', 'galleryImages', 'cardImage']);
        return Response($response, 200);
    }

    public function update(TicketRequest $request, Ticket $ticket)
    {
        $ticket = ServiceCrud::update($request);
        return Response($ticket, 200);
    }


}
