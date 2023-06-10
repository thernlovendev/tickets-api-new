<?php

namespace App\Services\NavigationMenus;

use App\Models\NavigationMenu;
use DB;

class ServiceCrud
{
	public static function create($data)
	{
		try {
            DB::beginTransaction();

            $navigation_menu = NavigationMenu::create(
                [
                    'name' => $data['name'],
                    'url' => $data['url'] ?? null,
                    'template_id' => $data['template_id'] ?? null,
                ]);

            DB::commit();

            return $navigation_menu->load('navigationSubMenus');

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

	public static function update($data, $navigation_menu)
	{
		try{
            DB::beginTransaction();
            $navigation_menu->update($data);
            DB::commit();
            return $navigation_menu;

        } catch (\Exception $e){
            DB::rollback();
            return $e;
        }
	}

	public static function delete($navigation_menu)
	{
        $navigation_menu->delete();
        return $navigation_menu;
    }

    public static function response($navigation_menu)
    {
        return $navigation_menu;
    }
}