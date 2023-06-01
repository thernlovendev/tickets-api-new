<?php

namespace App\Services\Templates;
use App\Models\Template;
use DB;
use App\Utils\ModelCrud;
use App\Services\Images\Service as ImageService;

class ServiceCrud
{
	public static function create($data)
	{
		try {
            DB::beginTransaction();

            $template = Template::create(
                [
                    'title' => $data['title'],
                    'type' => $data['type'],
                    'header_gallery_id' => $data['header_gallery_id'],
                    'content' => $data['content'],
                    'status' => $data['status'],
                    'created_by' => $data['created_by'],
                ]);

            DB::commit();

            return $template->load('navigationSubMenus');

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

	public static function update($data, $template)
	{
		try{
            DB::beginTransaction();

            $template->update($data);

            DB::commit();
            return $template->load('navigationSubMenus');

        } catch (\Exception $e){
            DB::rollback();
            return $e;
        }
	}

	public static function delete($template)
	{
        $template->delete();
        return $template;
    }

    public static function response($template)
    {
        return $template;
    }
}