<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\BookingRequest;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;
use App\Models\Ticket;
use App\Services\Bookings\ServiceGeneral;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
       $email = Auth::user()->email;
       $reservation = Reservation::with(['reservationItems.reservationSubItems'])->where('email',$email);
       $params = $request->query();
       $elements = ServiceGeneral::filterCustom($params, $reservation);
       $elements = $this->httpIndex($elements, []);
       $response = ServiceGeneral::mapCollection($elements);
       return Response($response, 200);
    }

    public function orderLookup(BookingRequest $request){
        $data = $request->validated();
        $order = Reservation::with('reservationItems.reservationSubItems')->where('order_number',$data['order_number'])->where('email',$data['email'])->first();
        
        if($order){
            $response = $order;
        } else {
            $response = "The email is not associated with the order";
        }

        return Response($response, 200);

    }

    public function dateChange(BookingRequest $request, ReservationSubItem $sub_item){
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $sub_item->rq_schedule_datetime = $data['rq_schedule_datetime'];
            
            $ticket_attach = Ticket::find($sub_item['ticket_id']);
            if($ticket_attach->type == TICKET::TYPE['GUIDE_TOUR'] ){
                $sub_item->ticket_sent_status = ReservationSubItem::SEND_STATUS['SENT'];
            } 

            $sub_item->save();
			
            DB::commit();

            return Response($sub_item, 200);

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }
        
        return $sub_item;

    }
    
}
