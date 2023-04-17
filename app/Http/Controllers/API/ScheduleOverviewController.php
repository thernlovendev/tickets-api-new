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

        $schedule = ReservationSubItem::with(['reservationItem', 'ticket'])->orderBy('rq_schedule_datetime', 'asc') 
            ->whereBetween('rq_schedule_datetime', [$start_date, $end_date])
            ->get()
            ->groupBy(function ($item) {
                return $item->ticket->title_en;
            })->map(function ($item, $key) {
                foreach ($item as $index => $value) {
                    if($value->reservationItem->adult_child_type == 'Child'){
                        $item[$index]['child_quantity'] = $value->reservationItem->quantity; 
                        $item[$index]['type'] = 'Child'; 
                    } else {
                        $item[$index]['adult_quantity'] = $value->reservationItem->quantity;
                        $item[$index]['type'] = 'Adult'; 
                    }
                }
                return $item->groupBy(function ($item) {
                    $date = Carbon::parse($item->rq_schedule_datetime)->format('Y-m-d');
                    return $date;
                })->map(function ($grouped_items) {
                        return [
                            'child_quantity' => $grouped_items->sum('child_quantity'),
                            'adult_quantity' => $grouped_items->sum('adult_quantity'),
                            'tiket_id' => $grouped_items->first()->ticket_id,
                        ];
                });

            });

        // $reservationSubItem = ReservationSubItem::with('reservationItem')->whereBetween('rq_schedule_datetime', [$start_date, $end_date])->get();

        // $schedule = $reservationSubItem->map( function($item){
        //     $adult_quantity = 0;
        //     $child_quantity = 0;

        //     $reservationItem = $item->reservationItem;

        //     if($reservationItem->adult_child_type == 'Child'){
        //         $child_quantity = $reservationItem->quantity;
        //     } else {
        //         $adult_quantity = $reservationItem->quantity;
        //     }

        //     return [
        //         'id' => $item->id,
        //         'reservation_id' =>$reservationItem->reservation_id, 
        //         'rq_schedule_datetime' => $item->rq_schedule_datetime,
        //         'ticket' => $item->ticket_id,
        //         'adult_quantity' => $adult_quantity,
        //         'child_quantity' => $child_quantity,
        //     ];
        // });

        
       return $schedule;
        


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
