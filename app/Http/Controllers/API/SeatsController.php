<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductSeat;
use App\Services\ProductSeats\ServiceGeneral;
use Illuminate\Http\Request;

class SeatsController extends Controller
{
    public function index(Request $request)
    {
        $tickets = ProductSeat::with([]);
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $tickets);
        $elements = $this->httpIndex($elements, []);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductSeat  $productSeat
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSeat $productSeat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductSeat  $productSeat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductSeat $productSeat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductSeat  $productSeat
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSeat $productSeat)
    {
        //
    }
}
