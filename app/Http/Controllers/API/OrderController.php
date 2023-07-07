<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OrderLookupRequest;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;

class OrderController extends Controller
{
    public function orderLookup(OrderLookupRequest $request){
        
        $order = Reservation::with('reservationItems.reservationSubItems')->where('order_number',$request->order_number)->where('email',$request->email)->first();
        
        if($order){
            $response = $order;
        } else {
            $response = "The email is not associated with the order";
        }

        return Response($response, 200);

    }
}
