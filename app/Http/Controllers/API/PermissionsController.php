<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class PermissionsController extends Controller
{
    public function userPermission(Request $request)
    {
        $roles = Auth::user()->roles;
        
        $permissions = [];

        foreach ($roles as $role) {
            $permissions = array_merge($permissions, Role::findByName($role['name'])->permissions->pluck('name')->toArray());
        }
        
        return Response($permissions, 200);
    }
}
