<?php 

namespace App\Http\Controllers;

use Illuminate\Pagination\Paginator;

trait HttpResponse
{
	public static function filter($model_query, $params)
	{
		$request = request();
		$filters = collect($request->query())->only($params);

		foreach ($filters as $key => $value) {
			$value = utf8_encode($value);
			if (mb_strpos($value, '%') === false) {
				if($value){
					$model_query->where($key, $value);
				}
			}
			else {
				$model_query->where($key, 'LIKE', $value);
			}
		}
		return $model_query;
	}

	public static function sort($model_query)
	{
		$request = request();
		$sort_request = $request->query('sort');
		$sorts = explode(',', $sort_request);
		$sorts = collect($sorts)->filter();
		$sorts = collect($sorts)->map(function ($item, $key) {
			$key = $item;
			$method = 'ASC';
			if (mb_strpos($key, '-') === 0) {
				$key = mb_substr($item, 1);
				$method = 'DESC';
			}
			return [
				'key' => $key, 
				'method' => $method,
			];
		});

		foreach($sorts as $sort) {
			$model_query->orderBy($sort['key'], $sort['method']);
		}
		return $model_query;
	}

	public static function paginate($model_query)
	{		
		$request = request();
		$page = $request->query('page');
		$per_page = $request->query('per_page');
		$results = $model_query;

		if (is_numeric($per_page)) {
			$results = $model_query->paginate($per_page);
		}
		else {
			return $model_query->get();
		}

		return [
			'page' => (int) $results->currentPage(), 
			'per_page' => (int) $results->perPage(), 
			'total' => (int) $results->total(),
			'data' => $results->getCollection(), 
		];
	}

	public function httpIndex($model_query, $filters = [])
	{
		if (count($filters) > 0) {
			$model_query = HttpResponse::filter($model_query, $filters);
		}
		$model_query = HttpResponse::sort($model_query);
		return HttpResponse::paginate($model_query);
	}
}