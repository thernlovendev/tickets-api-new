<?php

namespace App\Http\Controllers\API;

use App\Services\Tickets\ServiceCrud;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;
use App\Models\TicketScheduleException;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;
use App\Services\Tickets\ServiceGeneral;
use DB;

class TicketsController extends Controller
{

    public function index(Request $request)
    {
        $tickets = Ticket::with(['categories','subcategories','cardImage','iconImage', 'ticketPrices','ticketContent']);
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
        $response = $ticket->load(['categories', 'subcategories', 'ticketPrices', 'ticketContent', 'ticketSchedules.ticketScheduleExceptions', 'galleryImages', 'cardImage','iconImage']);
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

    public function getSold(Ticket $ticket, Request $request){

        // Option one
        //Params query, and another route.
        // $tickets_id = [1,2,26];

        // $items = ReservationSubItem::whereIn('ticket_id', $tickets_id);

        // $quantity_sold_test = $items->join('reservation_items', 'reservation_sub_items.reservation_item_id', '=', 'reservation_items.id')->selectRaw('ticket_id, sum(reservation_items.quantity) as total_quantity_sold')->groupBy('ticket_id')->get();

        // return Response($quantity_sold_test, 200);


        // Option two
        // $filter_datetime = false;
        // $datetime = null;
        // if($request->filled('rq_schedule_datetime')){
        //     $datetime = $request->input('rq_schedule_datetime');
        //     $filter_datetime = true;
        // }

        // $quantity_sold = ReservationItem::whereHas('reservationSubItems', function($query) use ($ticket, $datetime, $filter_datetime){
        //     $query->where('ticket_id', $ticket->id);
        //     if($filter_datetime){
        //         $query->where('rq_schedule_datetime', $datetime);
        //     }
        // })->sum('quantity');

        // $response = [
        //     'total_quantity_sold' => $quantity_sold
        // ];

        // return Response($response, 200);

        //option three

        // $tickets = [1,2,26];
        // $items = ReservationSubItem::whereIn('ticket_id', $tickets);

        $items = ReservationSubItem::where('ticket_id', $ticket->id);

        $quantity_sold_test = $items->join('reservation_items', 'reservation_sub_items.reservation_item_id', '=', 'reservation_items.id')->selectRaw('ticket_id, rq_schedule_datetime, sum(reservation_items.quantity) as total_quantity_sold')->groupBy('ticket_id','rq_schedule_datetime')->get();

        return Response($quantity_sold_test, 200);

    }

    public function multiple(Request $request)
    {
        $ids_filter = $request->input('ids_filter');
        $tickets = Ticket::whereIn('id',$ids_filter)->with(['categories','subcategories','cardImage', 'ticketPrices','ticketContent']);
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $tickets);
        $elements = $this->httpIndex($elements, ['id', 'status']);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
    }

    public function validatePick(Ticket $ticket,Request $request){
        
        $quantity = $request->input('quantity');
        $datetime = $request->input('datetime');
        $date = date("Y-m-d", strtotime($datetime));
        $time = date("H:i:s", strtotime($datetime));

        $date_selected = TicketScheduleException::where('date',$date)->where('time',$time)->whereHas('ticketSchedule', function ($query) use ($ticket) {
            $query->where('ticket_id', $ticket->id);
        })->first();
        
        $available_seats = $date_selected->max_people - $date_selected->total_quantity_sold; 


        if($available_seats < $quantity){
            return Response('투어 정원 초과 
            <br>  
            고객님이 선택하신 날짜에는 현재 예약 가능한 인원이 '.$available_seats.' 까지 선택할수 있습니다. 다른 일정을 선택하시거나 가능한 인원으로 변경해 주십시요',422);
        }

        return Response('Successfully validated', 200);

    }

    public function validatePickInBigApplePass(Request $request){

        $quantity = $request->quantity;
        $all_tickets = $request->tickets;
        $tickets = array_filter($all_tickets, function ($ticket) {
            return $ticket['ticket_type'] === 'Guide Tour';
        });

        $messages = [];

        foreach($tickets as $index => $ticket){
            $date = date("Y-m-d", strtotime($ticket['datetime']));
            $time = date("H:i:s", strtotime($ticket['datetime']));

            
            $date_selected = TicketScheduleException::where('date',$date)->where('time',$time)->whereHas('ticketSchedule', function ($query) use ($ticket) {
                $query->where('ticket_id', $ticket['ticket_id']);
            })->first();

            $available_seats = $date_selected->max_people - $date_selected->total_quantity_sold; 
            
            
            if($available_seats < $quantity){
                $ticket_name = Ticket::find($ticket['ticket_id']);
                $number = count($messages) + 1;
                $messages[$index] = $number.". ".$ticket_name->title_kr.": ".$available_seats." 까지 예약가능";
            }
        }

            if(count($messages) > 0){
                $message = implode("<br>", $messages);
                return Response('고객님이 선택하신 <span style="font-weight:800">투어티켓</span> 날짜에는 현재 예약 가능한 인원이 아래와 같습니다. 다른 일정을 선택하시거나 가능한 인원으로 변경해 주십시요.
            <br> 
            <br>
            '.$message,422);
            }else {
                return Response('Successfully validated', 200);
            }
    }



}
