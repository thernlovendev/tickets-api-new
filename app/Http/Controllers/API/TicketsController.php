<?php

namespace App\Http\Controllers\API;

use App\Services\Tickets\ServiceCrud;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;
use App\Services\Tickets\ServiceGeneral;
use DB;

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
        $data = $request->validated();
        $ticket = ServiceCrud::create($data);
        return Response($ticket, 201);
    }

    public function show(Ticket $ticket)
    {
        $response = $ticket->load(['categories', 'subcategories', 'ticketPrices', 'ticketContents', 'ticketSchedules', 'wideImages', 'galleryImages', 'cardImage']);
        return Response($response, 200);
    }

    public function update(TicketRequest $request, Ticket $ticket){
        try{
            DB::beginTransaction();
                $data = $request->validated();
                $ticket_updated = ServiceCrud::update($data, $ticket);
               
                DB::commit();
                return Response($ticket_updated, 200);
    
            } catch (\Exception $e){
                
                DB::rollback();
                return Response($e->errors(), 422);
            }
    
        }
    public function getSinglePrice(Ticket $ticket)
    {
        $response = $ticket->TicketPrices()->get();
        return Response($response, 200);
    }


}
