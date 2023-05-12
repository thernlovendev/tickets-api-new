<?php

namespace App\Http\Controllers\API;

use App\Services\Inventories\ServiceCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InventoryRequest;
use App\Http\Requests\StockTicketRequest;
use App\Imports\TicketStocksImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\TicketStock;
use App\Services\Inventories\ServiceGeneral;

class InventoriesController extends Controller
{

    public function index(Request $request)
    {
        $stock = TicketStock::query();
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $stock);
        $elements = $this->httpIndex($elements, []);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
        
    }

    public function register(InventoryRequest $request)
    {
        $inventory = ServiceCrud::register($request);
        return $inventory;
    }

    public function bulkUpload(StockTicketRequest $request)
    {
        $data = $request->validated();

        Excel::import(new TicketStocksImport($data), $data['file_import']);
        return Response(['message'=> 'Successful Bulk Up'], 200);
    }
}
