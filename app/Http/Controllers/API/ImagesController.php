<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Services\Images\Service as ImageService;
use Validator;

class ImagesController extends Controller
{
    public function index()
    {
        $query = Image::query();
        return response($this->httpIndex($query), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:5120',
        ]);
        if( $validator->fails() ){
            return response($validator->errors(), 400);
        }

        $path = $request->file('file')->storePublicly('images', 'public');
       
        $data = [
            'path' => $path
        ];
        $image = ImageService::create($data);
        return response($image, 201);
    }
}
