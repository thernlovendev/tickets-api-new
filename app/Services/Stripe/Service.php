<?php


namespace App\Services\Stripe;


// use App\Exceptions\MercadoPago\MercadopagoSetupNotFoundException;

use Exception;
use Illuminate\Support\Facades\Http;

class Service
{
    private const URL = 'https://api.stripe.com/';
    private $access_token;

    public function __construct()
    {
        $this->access_token = config('services.stripe.secret');
    }
    
    /**
     * La ruta de este metodo solo se crea en ambiente local
     * para crear usuario de prueba de mercadopago
     */
    public function createCharge($data)
    {
        // \Log::debug($data);
        // $response = Http::withHeaders([
        //     // 'Authorization' => "Bearer ".$this->access_token,
        //     'u' => $this->access_token.':',
        //     // 'Content-type' => 'application/json'
        // ])->post(self::URL.'v1/charges', $data);+}

        $stripe = new \Stripe\StripeClient(
            $this->access_token
          );
          $response = $stripe->charges->create($data);

        return $response;
    }

    public function saveCard($data)
    {
        $stripe = new \Stripe\StripeClient(
            $this->access_token
          );
        $cus_response = $stripe->customers->create([
            'name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'description' => $data['last_name'] ?? ''.'-'.$data['first_name'] ?? ''.'-'.$data['departure_date'] ?? ''
        ]);

        $card_response = $stripe->tokens->create([
            'card' => [
                'number' => $data['card']['number'],
                'exp_month' => $data['card']['exp_month'],
                'exp_year' => $data['card']['exp_year'],
                'cvc' => $data['card']['cvc'],
            ],
        ]);

        if(!$cus_response || !$card_response)   throw new Exception('Error!');

        // create and save card on stripe
        $card_response = $stripe->customers->createSource(
            $cus_response->id,
            [
                'source' => $card_response->id
            ]
        );
        return $card_response;
    }

    public function createTokenCreditCard($data){
        $stripe = new \Stripe\StripeClient($this->access_token);

        $response = $stripe->tokens->create([
        'card' => [
            'number' => $data['credit_number'],
            'exp_month' => $data['exp_month'],
            'exp_year' => $data['exp_year'],
            'cvc' => $data['cvc']
        ],
        ]);

        return $response;
    }
    
}