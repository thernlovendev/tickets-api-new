<?php


namespace App\Services\Stripe;


// use App\Exceptions\MercadoPago\MercadopagoSetupNotFoundException;
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

    
}