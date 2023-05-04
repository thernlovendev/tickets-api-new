<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        $response = Role::get();
        return Response($response, 200);
        
    }
}
