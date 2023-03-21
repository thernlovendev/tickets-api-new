<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Cities\ServiceCrud;
use App\Models\City;
use App\Http\Requests\CityRequest;

class CitiesController extends Controller
{

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
