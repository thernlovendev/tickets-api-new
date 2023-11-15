<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateRequest;
use App\Http\Requests\TemplateImageRequest;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Services\Templates\ServiceGeneral;
use App\Services\Templates\ServiceCrud;
use DB;

class TemplatesController extends Controller
{
    public function index(Request $request)
    {
        $templates = Template::with(['navigationSubMenus']);
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom ($params, $templates);
        $elements = $this->httpIndex($elements, ['id']);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
    }
    
    public function show(Template $template)
    {
        $response = $template->load(['navigationSubMenus']);
        return Response($response, 200);
    }

    public function store(TemplateRequest $request)
    {
        $data = $request->validated();
        $template = ServiceCrud::create($data);
        return Response($template, 201);
    }

    public function update(TemplateRequest $request, Template $template){
        try{
            
            DB::beginTransaction();
            $data = $request->validated();
            $template_updated = ServiceCrud::update($data, $template);
            
            DB::commit();
            return Response($template_updated, 200);
    
        } catch (\Exception $e){
            
            DB::rollback();
            return Response($e->errors(), 422);
        }
    }


    public function delete(Template $template){

        if($template->type == Template::TYPE['EMAIL']){
            return Response(['message'=> 'Not allowed to delete a template for email'], 400);
        }else{
            $template->delete();     
            
            return Response(['message'=> 'Delete Template Successfully'], 204);
        }

    }

    public function createTemplateImage(TemplateImageRequest $request)
    {
        $data = $request->validated();
        $template = ServiceCrud::createImage($data);
        return Response($template, 201);
    }

    public function updateImage(TemplateImageRequest $request, Template $template){
        try{
            
            DB::beginTransaction();
            $data = $request->validated();
            $template_updated = ServiceCrud::updateImage($data, $template);
            
            DB::commit();
            return Response($template_updated, 200);
    
        } catch (\Exception $e){
            
            DB::rollback();
            return Response($e->errors(), 422);
        }
    }

    public function showTemplateImage(Template $template)
    {
        $response = $template->load('image');

        return Response($response, 200);
    }

    public function reciveWebPage(Template $template){
        
        if($template->type == Template::TYPE['WEB_PAGE']){
            
            $header = $template->headerGallery;
            $content = $template->content;
            $header_main_image = $header->mainImage;
            $header_gallery_images = $header->galleryImages()->get();
            $first_phrase_header = $header->first_phrase;
            $second_phrase_header = $header->second_phrase;


            return [
                'header_main_image' => $header_main_image,
                'header_gallery_images' => $header_gallery_images,
                'first_phrase_header' => $first_phrase_header,
                'second_phrase_header' => $second_phrase_header,
                'content_page' => $template->content
            ];

        } else {
            return Response(['errors' => 'The ID template is Not Web Page'],422);
        }
    }
}
