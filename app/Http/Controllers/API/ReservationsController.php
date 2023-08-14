<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\ReservationByUserRequest;
use App\Http\Requests\ReservationPaymentRequest;
use App\Http\Requests\OptionScheduleRequest;
use App\Http\Requests\ReservationCardRequest;
use App\Services\Reservations\ServiceCrud;
use App\Services\Reservations\CreateByUser\ServiceCrud as ReservationByUserCrud;
use App\Models\Reservation;
use App\Models\ReservationSubItem;
use App\Models\OptionSchedule;
use App\Services\Reservations\ServiceGeneral;
use App\Services\Reservations\ServiceCashPayment;
use App\Services\Reservations\ServiceCreditCard;
use App\Exceptions\StripeTokenFailException;

use DB;

class ReservationsController extends Controller
{

    public function index(Request $request)
    {
       $reservation = Reservation::with(['reservationItems.reservationSubItems.optionsSchedules','vendorComissions']);
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

        } catch (StripeTokenFailException $e) {
            DB::rollback();
            return $e->render($request);
        } catch (\Exception $e){
            
            DB::rollback();
            return Response($e, 422);
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

    public function delete(Reservation $reservation){
        $reservation->delete();     
       
        return response()->json([
            'message'=> 'Delete Reservation Successfully'
        ]);
   }

   public function createByUser(ReservationByUserRequest $request)
    {
        try{
            DB::beginTransaction();
            $reservation = ReservationByUserCrud::create($request->validated());

            DB::commit();
            return Response($reservation, 201);
        } catch (\Exception $e){
            
            DB::rollback();
            return Response($e, 422);
        }
    }

    public function saveCard(ReservationCardRequest $request){
        try{
            DB::beginTransaction();
            $reservation = ServiceCreditCard::saveCardInfo($request->validated());

            DB::commit();
            return Response($reservation, 201);
        } catch (\Exception $e){
            
            DB::rollback();
            return Response($e, 422);
        }
    }
    public function filterScheduleOptions(Request $request) {

        if($request->filled('reservation_sub_item_id')){
           $response = OptionSchedule::whereIn('reservation_sub_item_id', $request->input('reservation_sub_item_id'))->get();
        } else {
            $response = OptionSchedule::get();
        }

        return $response;
    }

    public function getScheduleOptions(ReservationSubItem $reservation_sub_item) {
        return Response($reservation_sub_item->optionsSchedules()->orderBy('order')->get(), 200);
    }

    public function createScheduleOptions(ReservationSubItem $reservation_sub_item,OptionScheduleRequest $request) {
        $data = $request->validated();

        if($reservation_sub_item->optionsSchedules->isEmpty()){
            $reservation_sub_item->optionsSchedules()->createMany($data['schedules']);

            return Response($reservation_sub_item->load('optionsSchedules'),201);
        }else {

            return Response(['message'=> 'Schedule preferences have already been added'],422);
        }
        
    }

    public function updateByUser(ReservationByUserRequest $request, Reservation $reservation){
        try{
            DB::beginTransaction();
                $data = $request->validated();
                $reservation_updated = ReservationByUserCrud::update($data, $reservation);
                
                DB::commit();
                return Response($reservation_updated, 200);

            } catch (StripeTokenFailException $e) {
                DB::rollback();
                return $e->render($request);
            } catch (\Exception $e){
                DB::rollback();
                return Response($e, 422);
            }

    }
}
