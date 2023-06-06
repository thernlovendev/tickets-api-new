<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeaderGalleryRequest;
use App\Models\HeaderGallery;
use App\Services\HeaderGalleries\ServiceCrud;
use App\Services\HeaderGalleries\ServiceGeneral;
use Illuminate\Http\Request;
use DB;

class HeaderGalleryController extends Controller
{
    public function index(Request $request)
    {
        $header_galleries = HeaderGallery::with(['mainImage', 'galleryImages']);
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $header_galleries);
        $elements = $this->httpIndex($elements, ['id', 'title', 'is_show']);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
    }

    public function show(HeaderGallery $header_gallery)
    {
        $response = $header_gallery->load(['mainImage', 'galleryImages']);
        return Response($response, 200);
    }

    public function store(HeaderGalleryRequest $request)
    {
        $data = $request->validated();
        $header_gallery = ServiceCrud::create($data);
        return Response($header_gallery, 201);
    }

    public function update(HeaderGalleryRequest $request, HeaderGallery $header_gallery){
        try{
            DB::beginTransaction();
                $data = $request->validated();
                $header_gallery_updated = ServiceCrud::update($data, $header_gallery);
               
                DB::commit();
                return Response($header_gallery_updated, 200);
    
            } catch (\Exception $e){
                
                DB::rollback();
                return Response($e->errors(), 422);
            }
    
        }

    public function delete(HeaderGallery $header_gallery){

        $header_gallery->delete();     
        
        return Response(['message'=> 'Delete Header Gallery Successfully'], 204);
    }
}
