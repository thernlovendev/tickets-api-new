<?php

namespace App\Http\Controllers\API;

use App\Services\Tickets\ServiceCrud;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;
use App\Services\Tickets\ServiceGeneral;
use DB;

class TicketsController extends Controller
{

    public function index(Request $request)
    {
        $tickets = Ticket::with(['categories','subcategories','cardImage', 'ticketPrices','ticketContent']);
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $tickets);
        $elements = $this->httpIndex($elements, ['id', 'status']);
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
        $response = $ticket->load(['categories', 'subcategories', 'ticketPrices', 'ticketContent', 'ticketSchedules.ticketScheduleExceptions', 'wideImages', 'galleryImages', 'cardImage']);
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

    public function delete(Ticket $ticket){

        if($ticket->reservationSubItems->isEmpty()){
            $ticket->delete();     
            return Response(['message'=> 'Delete Ticket Successfully'], 204);
        } else {
            return Response(['message'=> 'The ticket is already used, cannot be delete'],422);
        }

        
    }

    public function getSold(Ticket $ticket){
        //Params query, and another route.
        // $tickets_id = [1,2,26];

        // $items = ReservationSubItem::whereIn('ticket_id', $tickets_id);

        // $quantity_sold_test = $items->join('reservation_items', 'reservation_sub_items.reservation_item_id', '=', 'reservation_items.id')->selectRaw('ticket_id, sum(reservation_items.quantity) as total_quantity_sold')->groupBy('ticket_id')->get();

        // return Response($quantity_sold_test, 200);


        $quantity_sold = ReservationItem::whereHas('reservationSubItems', function($query) use ($ticket){
            $query->where('ticket_id', $ticket->id);
        })->sum('quantity');

        $response = [
            'total_quantity_sold' => $quantity_sold
        ];

        return Response($response, 200);
    }


}
