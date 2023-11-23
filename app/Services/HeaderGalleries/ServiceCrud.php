<?php

namespace App\Services\HeaderGalleries;

use App\Models\HeaderGallery;
use DB;
use App\Services\Images\Service as ImageService;

class ServiceCrud
{
	public static function create($data)
	{
		try {
            DB::beginTransaction();

            $header_image = HeaderGallery::create(
                [
                    'title' => $data['title'],
                    'first_phrase' => $data['first_phrase'],
                    'second_phrase' => $data['second_phrase'],
                    'is_show' => $data['is_show'],
                ]);

                if(isset($data['galleries']) && !empty($data['galleries'])){
                    foreach($data['galleries'] as $image){
                        ImageService::attach($image, $header_image);
                    }
                }

            ImageService::attach($data['main_image'], $header_image);

            DB::commit();

            return $header_image->load('galleryImages', 'mainImage');

        } catch (\Exception $e){
            DB::rollback();
            return Response($e, 400);
        }

	}

	public static function update($data, $header_image)
	{
		try{
            DB::beginTransaction();

            $header_image->update($data);

            $main_image = collect($data['main_image']);

            if($header_image->mainImage->id !== $data['main_image']['id']){
                $header_image->mainImage->delete();
                ImageService::attach($main_image, $header_image);
            } 

            $gallery_images = collect($data['galleries']);

            $gallery_image_old = $header_image->galleryImages()->pluck('id');

            $gallery_image_request = collect($gallery_images)->whereNotNull('id')->pluck('id');
            
            $gallery_images_to_delete = $gallery_image_old->diff($gallery_image_request)->all();
            
            //delete images gone

            foreach($gallery_images_to_delete as $gallery_image_id) {
                $header_image->galleryImages()->where('id', $gallery_image_id)->delete();
            }

            //Create or update
            foreach ($data['galleries'] as $gallery_image) {
                $gallery_image_update = $header_image->galleryImages()->find($gallery_image['id']);
                if($gallery_image_update){
                    $gallery_image_update->update([
                        'priority' => $gallery_image['priority'],
                        'priority_type' => $gallery_image['priority_type'],
                    ]); 
                } else {
                    ImageService::attach($gallery_image, $header_image);
                }
            }

            DB::commit();
            return $header_image->load('galleryImages', 'mainImage');

        } catch (\Exception $e){
            DB::rollback();
            return $e;
        }
	}

	public static function delete($header_image)
	{
        $header_image->delete();
        return $header_image;
    }

    public static function response($header_image)
    {
        return $header_image;
    }
}