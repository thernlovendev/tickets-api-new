<?php

namespace App\Services\Reservations;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\PriceList;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;

class ServiceCashPayment
{
	public static function create($reservation, $data)
	{
		try {
            DB::beginTransaction();
            
            $data['debit'] = $data['credit'] - $data['total'];
            
            $reservation->reservationCashPayments()->create($data);
            
            $reservation->payment_type = Reservation::PAYMENT_TYPE['CASH'];
            $reservation->status = Reservation::STATUS['PAID'];
            
            $reservation->save();

            DB::commit();

            return $reservation->load(['reservationItems.reservationSubItems','vendorComissions']);

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

}