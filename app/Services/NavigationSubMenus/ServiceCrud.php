<?php

namespace App\Services\NavigationSubMenus;

use App\Models\NavigationSubMenu;
use DB;

class ServiceCrud
{
	public static function create($data)
	{
		try {
            DB::beginTransaction();
            $data_insert = [];
            $i = 0;
            foreach($data['navigation_submenus'] as $key => $value)  {
                $data_insert[$i] = $value;
                $data_insert[$i]['ticket_ids'] = json_encode($value['ticket_ids']);
                $data_insert[$i]['navigation_menu_id'] = $data['navigation_menu_id'];
                $i++;
            }
            NavigationSubMenu::insert($data_insert);
            $last_navigation_submenus = NavigationSubMenu::orderBy('id', 'desc')->take(count($data_insert))->get();
            DB::commit();
            
            return $last_navigation_submenus;

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

	public static function update($data)
	{
		try{
            DB::beginTransaction();
            $data_update = [];
            $i = 0;
            foreach($data['navigation_submenus'] as $key => $value)  {
                $data_update[$i] = $value;
                $data_insert[$i]['ticket_ids'] = json_encode($value['ticket_ids']);
                $data_update[$i]['navigation_menu_id'] = $data['navigation_menu_id'];
                $i++;
            }

            foreach($data_update as $key => $value) {
                NavigationSubMenu::where('id', $value['id'])->update($value);
            }
            $last_navigation_submenus = NavigationSubMenu::orderBy('updated_at', 'desc')->take(count($data_update))->get();
            DB::commit();
            return $last_navigation_submenus;

        } catch (\Exception $e){
            DB::rollback();
            return $e;
        }
	}

	public static function delete($navigation_submenu)
	{
        $navigation_submenu->delete();
        return $navigation_submenu;
    }

    public static function response($navigation_submenu)
    {
        return $navigation_submenu;
    }
}