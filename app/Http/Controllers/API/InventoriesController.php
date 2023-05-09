<?php

namespace App\Http\Controllers\API;

use App\Services\Inventories\ServiceCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InventoryRequest;
use App\Http\Requests\StockTicketRequest;
use App\Imports\TicketStocksImport;
use Maatwebsite\Excel\Facades\Excel;

class InventoriesController extends Controller
{
    public function register(InventoryRequest $request)
    {
        $inventory = ServiceCrud::register($request);
        return $inventory;
    }

    public function bulkUpload(StockTicketRequest $request)
    {
        $data = $request->validated();

        dd($data);

        Excel::import(new UsersImport, 'users.xlsx');
        return Response($data, 200);
    }
}
