<?php

namespace App\Http\Controllers\API;

use App\Services\Inventories\ServiceCrud;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InventoryRequest;

class InventoriesController extends Controller
{
    public function register(InventoryRequest $request)
    {
        $inventory = ServiceCrud::register($request);
        return $inventory;
    }
}
