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

        $stocks = $stock->join('tickets', 'ticket_stocks.ticket_id', '=', 'tickets.id')
        ->selectRaw('ticket_id, title_en, product_code, range_age_type, tickets.out_of_stock_alert_adult, tickets.out_of_stock_alert_child, count(*) as total, count(CASE WHEN ticket_stocks.status = "Valid" THEN 1 END) AS total_valid, MAX(ticket_stocks.created_at) AS last_update')
        ->groupBy('ticket_id', 'range_age_type');

        $elements = ServiceGeneral::filterCustom($params, $stocks);
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
