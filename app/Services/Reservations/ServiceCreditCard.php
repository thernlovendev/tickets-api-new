<?php

namespace App\Services\Reservations;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\PriceList;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;
use App\Services\Stripe\Service as ServiceStripe;

class ServiceCreditCard
{
	public static function create($reservation, $data)
	{
		try {
            DB::beginTransaction();

            $payload = [
                'source' => $data['token_stripe'],
                'amount' => $data['total'] * 100,
                'currency' => 'usd',
                'description' => $reservation->customer_name_en
            ];
            
            $service = new ServiceStripe();
            $response = $service->createCharge($payload);
            
            $data['payment_status'] = $response['outcome']['seller_message'];
            $data['card_type'] = $response['payment_method_details']['card']['brand'];

            $reservation->reservationCreditCardPayments()->create($data);
            
            $reservation->payment_type = Reservation::PAYMENT_TYPE['CREDIT_CARD'];
            $reservation->status = Reservation::STATUS['PAID'];
            
            $reservation->save();
            
            DB::commit();

            return $response;

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

}