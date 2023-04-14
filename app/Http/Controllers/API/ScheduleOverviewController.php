<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\ReservationSubItem;
use App\Models\ReservationItem;
use App\Models\Reservation;
use Carbon\Carbon;

class ScheduleOverviewController extends Controller
{
    public function index(Request $request){

        $params = $request->query();
        
        $start_date = Carbon::parse($params['start_date']);
        $end_date = Carbon::parse($params['end_date']);

        $reservation = Reservation::whereBetween('created_at', [$start_date, $end_date])->get();

        
        $tickets = Ticket::where('show_in_schedule_page',true)->get();
        
        dd($reservation);


        // $counter_quantity = [];
        // foreach ($tickets as $ticket){
        //     $sub_items = ReservationSubItem::with('reservationItem')->where('ticket_id', $ticket->id)->get();
            
        //     foreach($sub_items as $index => $sub_item){
                  
        //         $counter_quantity[$sub_item['id']][$index]['quantity'] = $sub_item['reservationItem']['quantity'];
        //     }
        // }
        // dd($counter_quantity);
        
        // return $tickets;
    }
}
