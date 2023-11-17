<?php


namespace App\Services\Square;
use Square\SquareClient;
use Square\Environment;
use Square\Models\CreatePaymentRequest;
use Exception;
use Illuminate\Support\Facades\Http;
use App\Exceptions\FailException;


class Service
{
    // private const URL = 'https://connect.squareup.com/v2/';
    private const URL = 'https://connect.squareupsandbox.com/v2/';

    private $access_token;

    public function __construct()
    {
        $this->access_token = config('services.square.access_token');
    }
    
    /**
     * La ruta de este metodo solo se crea en ambiente local
     * para crear usuario de prueba de mercadopago
     */
    public function createPayment($data)
    {

        $response = Http::withToken($this->access_token)->withHeaders([
            'Square-Version' => '2023-08-16',
        ])->post(self::URL.'payments', [
            'source_id' => $data['source'],
            'amount_money' => [
                'amount' => round($data['amount']),
                'currency' => $data['currency'],
            ],
            'note' => $data['description'],
            'idempotency_key' => uniqid(),
        ]);


        if($response->failed()){
            if(isset($response->json()['errors'])){
                throw new FailException($response->json()['errors'], 'Something went wrong');
            } else {
                throw new FailException($response->json(), 'Something went wrong');
            }
        }
        
        return $response->json();
        
    }
    
}