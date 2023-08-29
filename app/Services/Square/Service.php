<?php


namespace App\Services\Square;
use Square\SquareClient;
use Square\Environment;
use Square\Models\CreatePaymentRequest;
use Exception;
use Illuminate\Support\Facades\Http;

class Service
{
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
        $square_client = new SquareClient([
            'accessToken' => config('square.sandbox_access_token'),
            'environment' => Environment::SANDBOX,
        ]);
        
        $payments_api = $square_client->getPaymentsApi();
        
        dd($data);
        $create_payment_request = new CreatePaymentRequest([
            'source_id' => $data['source'], // Reemplaza con un nonce válido
            'amount_money' => [
                'amount' => $data['amount'], // El monto en centavos
                'currency' => $data['currency'],
            ],
        ]);
        
        try {
            $response = $payments_api->createPayment($create_payment_request);
            return $response;
        } catch (\Square\Exceptions\ApiException $e) {
            // Manejar la excepción
            \Log::debug('error excepton square');
            \Log::debug($e);
        
            // if(!$cus_response || !$card_response)   throw new Exception('Error!');
        }
    }
    
}