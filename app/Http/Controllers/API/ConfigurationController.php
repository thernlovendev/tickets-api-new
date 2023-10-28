<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentTypeRequest;
use App\Models\Configuration;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function getPaymentConfiguration(Request $request)
    {
        $response = Configuration::where('key','PAYMENT_TYPE')->first();
        return Response($response, 200);
    }
    
    public function updatePaymentConfiguration(PaymentTypeRequest $request)
    {
        $data = $request->validated();
        $payment_type = Configuration::where('key','PAYMENT_TYPE')->first();

        $payment_type->update($data);
        
        return Response($payment_type, 200);
    }
}
