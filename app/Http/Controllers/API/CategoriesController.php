<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Categories\ServiceCrud;
use App\Services\Categories\ServiceGeneral;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Subcategory;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::query();
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $categories);
        $elements = $this->httpIndex($elements, ['id', 'city_id','name']);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = ServiceCrud::create($request);
        return Response($category, 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $response = ServiceCrud::response($category);
        return Response($response->load('Subcategories'), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $update = ServiceCrud::update($data, $category);
        
        return Response($update, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getSubcategories(Request $request)
    {
       
        $query = $request->query();
        $sub_category = Subcategory::whereIn('category_id',$query['categories'])->get();
        return Response($sub_category, 200);
    }

    public function getSubcategoriesMultiple(Request $request)
    {
        $ids_filter = $request->input('ids_filter');
        $sub_category = Subcategory::whereIn('category_id',$ids_filter)->get();
        return Response($sub_category, 200);
    }
}
