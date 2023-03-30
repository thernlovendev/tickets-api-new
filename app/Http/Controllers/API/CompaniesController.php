<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Services\Companies\ServiceGeneral;

class CompaniesController extends Controller
{

    public function index(Request $request)
    {
        $companies = Company::query();
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $companies);
        $elements = $this->httpIndex($elements, ['id', 'name','status']);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
    }

}
