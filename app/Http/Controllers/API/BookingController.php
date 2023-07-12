<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OrderLookupRequest;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;
use App\Services\Bookings\ServiceGeneral;
use Illuminate\Support\Facades\Auth;


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

    public function orderLookup(OrderLookupRequest $request){
        $data = $request->validated();
        $order = Reservation::with('reservationItems.reservationSubItems')->where('order_number',$data['order_number'])->where('email',$data['email'])->first();
        
        if($order){
            $response = $order;
        } else {
            $response = "The email is not associated with the order";
        }

        return Response($response, 200);

    }
}
