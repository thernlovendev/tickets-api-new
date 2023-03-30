<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Cities\ServiceCrud;
use App\Models\City;
use App\Http\Requests\CityRequest;
use App\Services\Cities\ServiceGeneral;

class CitiesController extends Controller
{

    public function index(Request $request)
    {
        $cities = City::query();
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $cities);
        $elements = $this->httpIndex($elements, ['id', 'name','status']);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
    }

    public function getCitiesByCompany(City $company)
    {
        $cities = City::where('company_id', $company->id)->get();
        return Response($cities, 200);
    }

    public function store(CityRequest $request)
    {
        $cities = ServiceCrud::create($request);
        return $cities;
    }

}
