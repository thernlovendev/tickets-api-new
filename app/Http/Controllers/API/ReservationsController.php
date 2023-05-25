<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\ReservationPaymentRequest;
use App\Services\Reservations\ServiceCrud;
use App\Models\Reservation;
use App\Services\Reservations\ServiceGeneral;
use App\Services\Reservations\ServiceCashPayment;
use App\Services\Reservations\ServiceCreditCard;
use DB;

class ReservationsController extends Controller
{

    public function index(Request $request)
    {
       $reservation = Reservation::with(['vendorComissions']);
       $params = $request->query();
       $elements = ServiceGeneral::filterCustom($params, $reservation);
       $elements = $this->httpIndex($elements, ['id', 'order_number']);
       $response = ServiceGeneral::mapCollection($elements);
       return Response($response, 200);
    }

    public function store(ReservationRequest $request)
    {
        try{
            DB::beginTransaction();
            $reservation = ServiceCrud::create($request->validated());

            DB::commit();
            return Response($reservation, 201);
        } catch (\Exception $e){
            
            DB::rollback();
            return Response($e, 422);
        }
    }

    public function show(Reservation $reservation)
    {
        $response = $reservation->load(['reservationItems.reservationSubItems', 'vendorComissions']);
        return Response($response, 200);
    }

    public function update(ReservationRequest $request, Reservation $reservation){
    try{
        DB::beginTransaction();
            $data = $request->validated();
            $reservation_updated = ServiceCrud::update($data, $reservation);
           
            DB::commit();
            return Response($reservation_updated, 200);

        } catch (\Exception $e){
            
            DB::rollback();
            return Response($e->errors(), 422);
        }

    }

    public function payment(Reservation $reservation, ReservationPaymentRequest $request)
    {

            if($request->payment_type == Reservation::PAYMENT_TYPE['CASH']){
                $response = ServiceCashPayment::create($reservation, $request->all());
            }  else{
                $response = ServiceCreditCard::create($reservation, $request->all());
            }

            return Response($response, 201);
       
    }
}
