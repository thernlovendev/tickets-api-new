<?php

namespace App\Services\Images;
use App\Models\Image;

class Service
{
	public static function create($data)
	{
		$image = Image::create($data);
		return $image;
	}

	public static function attach($data, $model)
	{
		$image = Image::find($data['id']);
		$image->update([
			'imageable_id' => $model->id,
			'imageable_type' => get_class($model),
			'priority' => $data['priority'] ?? null,
            'priority_type' => $data['priority_type'] ?? null
		]);
		return $image;
	}

	public static function sync($data, $model)
	{
		$validator = Validator::make($data, [
            'images' => 'array|min:0',
			'images.*.id' => 'required|exists:'.Image::TABLE,
			// 'images.*.orden' => 'required|integer',
		]);
		$data = $validator->validate();
		$data = $data['images'];

		$images_id = collect($data)->pluck('id')->toArray();
		$images_detele = $model->images()->whereNotIn('id', $images_id)->get();

		try {
			DB::beginTransaction();
			foreach ($images_detele as $image_detele) {
				ServiceCrud::delete($image_detele);
			}
			foreach ($data as $image_data) {
				ServiceCrud::attach($image_data, $model);
			}
			DB::commit();
		} catch (Exception $e) {
			DB::rollBack();
			throw $e;
		}

		$images = $model->images()->get();
		return $images;
	}

	public static function delete($image)
	{
		$image->delete();
		return $image;
	}
}