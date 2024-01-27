<?php

namespace App\Services\Reservations;
use App\Models\ReservationMemo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class ServiceHistory
{
    //$data = array(memos)
	public static function save($data, $reservation, $action, $key = null)
	{
		try {
            DB::beginTransaction();

            $memo = $reservation->memos()->create([
                'description' => ServiceHistory::mapData($data),
                'key' => $key,
                'action' => $action,
                'user_id' => Auth::user()->id,
            ]);

            DB::commit();

        } catch (\Exception $e){
            \Log::debug($e);
            DB::rollback();
            return Response($e, 400);
        }

	}


    public static function create($model, $action, $reservation, $key = null){
        $data = $model->getAttributes();
        if($action == 'update'){
            $data = $model->getDirty();
        } 
        if(!empty($data)){

            ServiceHistory::save($data, $reservation, $action, $key);
        }
    }

    public static function deleteUpdateOrCreateMemo(Relation $relation, array $items, $reservation, $key = null)
    {

        $relation->get()->each(function($model) use ($items, $reservation) {
            foreach ($items as $key => $item) {

                if ($model->id === ($item['id'] ?? null)) {
                    $model->fill($item); //update
                    ServiceHistory::create($model, 'update', $reservation, 'Ticket '.$model->ticket->title_en);
                    $model->save();
                    return;
                }
            }

            ServiceHistory::create($model, 'delete', $reservation, 'Ticket '.$model->ticket->title_en);
            return $model->delete();
        });

        foreach ($items as $key => $item) {
            
            $item['id'] = isset($item['id']) ? $item['id'] : null;
            if (!$item['id']){
                $model = $relation->create($item);
                ServiceHistory::create($model, 'create', $reservation, 'Ticket '.$model->ticket->title_en);
            }

        };

        return true;
    }

    private static function mapData($data){
        // Transformar el array en el formato deseado
        $result = implode(', ', array_map(
            function ($key, $value) {
                $key = str_replace('_', ' ', $key); 
                return "$key: $value";
            },
            array_keys($data),
            $data
        ));
        // Imprimir el resultado
        return $result;
    }

}