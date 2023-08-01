<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PriceListRequest;
use App\Models\PriceList;
use App\Services\PriceLists\ServiceCrud;
use App\Services\PriceLists\ServiceGeneral;
use App\Models\Category;
use DB;

class PriceListsController extends Controller
{
 
    public function getByCategory(Request $request)
    {
        $params = $request->query();

        $price_lists = PriceList::where('category_id', $params['category_id'])
            ->get();
        $price_lists_group = $price_lists->groupBy('subcategory_id')->map(function($item){
            return [
                'subcategory_id' => $item->first()->subcategory_id,
                'prices' => $item
            ];
        });

        $subcategories_model = Category::find($params['category_id'])->subcategories()->pluck('id');
        $subcategories_empty = $subcategories_model->diff($price_lists->pluck('subcategory_id'));
        
        $merge = [];
        foreach ($subcategories_empty as $key => $value) {
            $merge[$value] = [
                'subcategory_id' => $value,
                'prices' => []
            ];
        }

        $price_lists_group = collect($price_lists_group)->merge($merge);

        $data = [
            'category_id' => $params['category_id'],
            'subcategories' => $price_lists_group
        ];
        return Response($data, 200);
    }

    public function getBySubcategory(Request $request)
    {
        $params = $request->query();

        $price_lists = PriceList::where('subcategory_id', $params['subcategory_id'])
            ->get();
            
        return Response($price_lists, 200);
    }

    public function store(PriceListRequest $request)
    {
        $ticket = ServiceCrud::create($request);
        return Response($ticket, 201);
    }

    public function update(PriceListRequest $request, PriceList $price_list){
        try{
            DB::beginTransaction();
                $data = $request->validated();
                $price_list_updated = ServiceCrud::update($data, $price_list);
               
                DB::commit();
                return Response($price_list_updated, 200);
    
            } catch (\Exception $e){
                
                DB::rollback();
                return Response($e->errors(), 422);
            }
    
        }

    public function delete(PriceList $price_list)
    {
        $price_list->delete();

        return response()->json([
            'message'=> 'Delete Reservation Successfully'
        ]);
    }

    public function show(PriceList $price_list)
    {
        $response = $price_list->load('subcategory');

        return $response;
    }

    public function index(Request $request)
    {
       $reservation = PriceList::with(['subcategory']);
       $params = $request->query();
       $elements = ServiceGeneral::filterCustom($params, $reservation);
       $elements = $this->httpIndex($elements, []);
       $response = ServiceGeneral::mapCollection($elements);
       return Response($response, 200);
    }

}
