<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NavigationSubMenuRequest;
use App\Models\NavigationSubMenu;
use App\Services\NavigationSubMenus\ServiceCrud;
use App\Services\NavigationSubMenus\ServiceGeneral;
use Illuminate\Http\Request;
use DB;

class NavigationSubMenuController extends Controller
{
    public function index(Request $request)
    {
        $navigation_submenus = NavigationSubMenu::with(['navigationMenu']);
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $navigation_submenus);
        $elements = $this->httpIndex($elements, ['id', 'name']);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
    }

    public function show(NavigationSubMenu $navigation_submenu)
    {
        $response = $navigation_submenu->load(['navigationMenu']);
        return Response($response, 200);
    }

    public function store(NavigationSubMenuRequest $request)
    {
        $data = $request->validated();
        $header_gallery = ServiceCrud::create($data);
        return Response($header_gallery, 201);
    }

    public function update(NavigationSubMenuRequest $request){
        try{
                DB::beginTransaction();
                $data = $request->validated();
                $navigation_menu_updated = ServiceCrud::update($data);
               
                DB::commit();
                
                return Response($navigation_menu_updated, 200);
    
            } catch (\Exception $e){
                
                DB::rollback();
                return Response($e->errors(), 422);
            }
    
    }

    public function delete(NavigationSubMenu $navigation_menu){

        $navigation_menu->delete();     
        
        return Response(['message'=> 'Delete Navigation Menu Successfully'], 204);
    }
}
