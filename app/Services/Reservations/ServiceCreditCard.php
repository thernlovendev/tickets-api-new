<?php

namespace App\Services\Reservations;
use DB;
use Validator;
use Illuminate\Validation\Rule;
use App\Models\PriceList;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\ReservationSubItem;
use App\Models\Configuration;
use App\Services\Stripe\Service as ServiceStripe;
use App\Services\Square\Service as ServiceSquare;
use App\Exceptions\FailException;

class ServiceCreditCard
{
	public static function create($reservation, $data)
	{
		try {
            DB::beginTransaction();
            
            $config_payment = Configuration::where('key', 'PAYMENT_TYPE')->first();
            
            if($config_payment->value == 'SQUARE'){
                $response = ServiceCreditCard::paymentSquare($reservation, $data);
            } else {
                $response = ServiceCreditCard::paymentStripe($reservation, $data);
            }
            
            DB::commit();

            return $response;

        } catch (FailException $e) {
            DB::rollback();
            return $e->render();
        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

    public static function saveCardInfo($data)
	{
		try {
            DB::beginTransaction();
            $service = new ServiceStripe();
            $response = $service->saveCard($data);
            
            DB::commit();

            return $response;

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

    private function paymentStripe($reservation, $data){

        $service = new ServiceStripe();     

            $payload = [
                'source' => $data['stripe_token'],
                'amount' => $data['total'] * 100,
                'currency' => 'usd',
                'description' => $reservation->customer_name_en
            ];
            
            
            $response = $service->createCharge($payload);
            
            $data['payment_status'] = $response['outcome']['seller_message'];
            $data['card_type'] = $response['payment_method_details']['card']['brand'];

            $reservation->reservationCreditCardPayments()->create($data);
            
            $reservation->payment_type = Reservation::PAYMENT_TYPE['CREDIT_CARD'];
            $reservation->status = Reservation::STATUS['PAID'];
            
            $reservation->save();

            return $response;
    }

    private function paymentSquare($reservation, $data){
        
        $service = new ServiceSquare();     

            $payload = [
                'source' => $data['stripe_token'],
                'amount' => $data['total'] * 100,
                'currency' => 'USD',
                'description' => $reservation->customer_name_en
            ];
            
            $response = $service->createPayment($payload);
           
            $data['payment_status'] = $response['payment']['status'];
            $data['card_type'] = $response['payment']['card_details']['card']['card_brand'];

            $reservation->reservationCreditCardPayments()->create($data);
            
            $reservation->payment_type = Reservation::PAYMENT_TYPE['CREDIT_CARD'];
            $reservation->status = Reservation::STATUS['PAID'];
            
            $reservation->save();

            return $response;
    }

}