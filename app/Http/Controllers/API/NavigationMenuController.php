<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NavigationMenuRequest;
use App\Models\NavigationMenu;
use App\Services\NavigationMenus\ServiceCrud;
use App\Services\NavigationMenus\ServiceGeneral;
use Illuminate\Http\Request;
use DB;

class NavigationMenuController extends Controller
{
    public function index(Request $request)
    {
        $navigation_menus = NavigationMenu::with(['navigationSubMenus']);
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $navigation_menus);
        $elements = $this->httpIndex($elements, ['id', 'name']);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
    }

    public function show(NavigationMenu $navigation_menu)
    {
        $response = $navigation_menu->load(['navigationSubMenus']);
        return Response($response, 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $header_gallery = ServiceCrud::create($data);
        return Response($header_gallery, 201);
    }

    public function update(Request $request, NavigationMenu $navigation_menu){
        try{
                DB::beginTransaction();
                $data = $request->all();
                $navigation_menu_updated = ServiceCrud::update($data, $navigation_menu);
               
                DB::commit();
                
                return Response($navigation_menu_updated, 200);
    
            } catch (\Exception $e){
                
                DB::rollback();
                return Response($e->errors(), 422);
            }
    
        }

    public function delete(NavigationMenu $navigation_menu){

        $navigation_menu->delete();     
        
        return Response(['message'=> 'Delete Navigation Menu Successfully'], 204);
    }
}
