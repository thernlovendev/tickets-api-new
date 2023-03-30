<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PriceListRequest;
use App\Services\PriceLists\ServiceCrud;

class PriceListsController extends Controller
{
    public function store(PriceListRequest $request)
    {
        $ticket = ServiceCrud::create($request);
        return Response($ticket, 201);
    }
}
