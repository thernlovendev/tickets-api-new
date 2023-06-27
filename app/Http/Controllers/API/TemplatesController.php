<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateRequest;
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
        $elements = $this->httpIndex($elements, ['id', 'title', 'type']);
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

        $template->delete();     
        
         return Response(['message'=> 'Delete Template Successfully'], 204);
    }
}