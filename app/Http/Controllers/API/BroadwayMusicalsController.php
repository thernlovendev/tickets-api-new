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
    public function selectSeat(Request $request, ServiceGeneral $broadwayService)
    {
        try {
            $jsonData = $request->json()->all();
            // build the xml
            $xml = SelectSeatRequest::build($jsonData);
            $response = $broadwayService->selectSeat(['xml' => $xml]);
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
    public function buySeat(Request $request, ServiceGeneral $broadwayService)
    {
        try {
            $jsonData = $request->json()->all();
            // build the xml
            $xml = BuySeatRequest::build($jsonData);
            $response = $broadwayService->buySeat(['xml' => $xml]);
            return response()->json(['data' => $response], 200);
        } catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
