<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BroadwayMusicals\ServiceGeneral;
use App\Http\Requests\BroadwayMusicals\SelectSeatRequest;
use App\Http\Requests\BroadwayMusicals\BuySeatRequest;

class BroadwayMusicalsController extends Controller
{    
    /**
     * selectSeat
     * Connect json to the soap xml server to select a ticket
     * 
     * @param  mixed $request
     * @return void
     */
    public function selectSeat(SelectSeatRequest $request, ServiceGeneral $broadwayService)
    {
        try {
            $jsonData = $request->json()->all();
            $response = $broadwayService->selectSeat($jsonData);

            $validator = json_encode($response);
            $validator_decode = json_decode($validator, true);
            
            if(isset($validator_decode['original'])){
                 $message = $validator_decode['original']['error'];
                 return Response(['error' => $message],400);
            }
            
            return response()->json(['data' => $response], 200);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * buySeat
     * Buy a ticket on the brodway api
     * @param  mixed $request
     * @param  mixed $broadwayService
     * @return void
     */
    public function buySeat(BuySeatRequest $request, ServiceGeneral $broadwayService)
    {
        try {
            $jsonData = $request->json()->all();
            $response = $broadwayService->buySeat($jsonData);
            return $response;
            return response()->json(['data' => $response], 200);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
