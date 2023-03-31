<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PriceListRequest;
use App\Models\PriceList;
use App\Services\PriceLists\ServiceCrud;
use App\Services\PriceLists\ServiceGeneral;

class PriceListsController extends Controller
{
 
    public function index(Request $request)
    {
        $price_lists = PriceList::query();
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $price_lists);
        $elements = $this->httpIndex($elements, ['id','subcategory_id','product_type','child_price','adult_price']);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
    }

    public function store(PriceListRequest $request)
    {
        $ticket = ServiceCrud::create($request);
        return Response($ticket, 201);
    }
}
