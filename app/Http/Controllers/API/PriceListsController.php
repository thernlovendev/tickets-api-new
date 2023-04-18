<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PriceListRequest;
use App\Models\PriceList;
use App\Services\PriceLists\ServiceCrud;
use App\Services\PriceLists\ServiceGeneral;
use App\Models\Category;

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

    public function store(PriceListRequest $request)
    {
        $ticket = ServiceCrud::create($request);
        return Response($ticket, 201);
    }
}
