<?php

namespace App\Services\Reservations;
use App\Models\ReservationMemo;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use DB;

class ServiceMemo
{
    //$data = array(memos)
	// public static function save($data, $reservation, $action, $key = null)
	// {
	// 	try {
    //         DB::beginTransaction();

    //         $memo = $reservation->memos()->create([
    //             'description' => $data,
    //             'key' => $key,
    //             'action' => $action,
    //             'user_id' => Auth::user()->id,
    //         ]);

    //         DB::commit();

    //     } catch (\Exception $e){
    //         \Log::debug($e);
    //         DB::rollback();
    //         return Response($e, 400);
    //     }

	// }

    // public static function create($model, $action, $reservation, $key = null){
    //     $data = $model->getAttributes();
    //     if($action == 'update'){
    //         $data = $model->getDirty();
    //     } 
    //     if(!empty($data)){

    //         ServiceMemo::save($data, $reservation, $action, $key);
    //     }
    // }

    public static function deleteUpdateOrCreateMemo(Relation $relation, array $items, $reservation, $key = null)
    {
        $authUserId = Auth::user()->id;
        
        $relation->get()->each(function($model) use ($items, $reservation, $authUserId) {
            $modelExistsInItems = false;
            foreach ($items as $key => $item) {
                if ($model->id === ($item['id'] ?? null) && $model->user_id === $authUserId) {
                    $modelExistsInItems = true;
                    // Actualizar registros si el id existe y pertenece al usuario autenticado
                    $model->fill($item);
                    $model->save();
                    return;
                }
            }
            // Eliminar registros si id es null y pertenecen al usuario autenticado
            if (!$modelExistsInItems && $model->user_id === $authUserId) {
                $model->delete();
            }
        });

        foreach ($items as $key => $item) {
            $item['id'] = isset($item['id']) ? $item['id'] : null;

            if (!$item['id']){
                $item['user_id'] = $authUserId;
                $item['action'] = 'ADD';
                $item['key'] = 'MEMO';
                $model = $relation->create($item);
            }
        };

        return true;
    }
    // public static function deleteUpdateOrCreateMemo(Relation $relation, array $items, $reservation, $key = null)
    // {
        
    //     $relation->get()->each(function($model) use ($items, $reservation) {
    //         foreach ($items as $key => $item) {

    //             if ($model->id === ($item['id'] ?? null)) {
    //                 $model->fill($item); //update
    //                 // ServiceMemo::save($data, $reservation, $action, $key);
    //                 $model->save();
    //                 return;
    //             }
    //         }

    //         return $model->delete();
    //     });

    //     foreach ($items as $key => $item) {
            
    //         $item['id'] = isset($item['id']) ? $item['id'] : null;
    //         if (!$item['id']){
    //             $item['user_id'] = Auth::user()->id;
    //             $item['action'] = 'ADD';
    //             $item['key'] = 'MEMO';
    //             $model = $relation->create($item);
    //             // ServiceMemo::create($model, 'delete', $reservation, 'MEMO');
    //         }

    //     };

    //     return true;
    // }

    // private static function mapData($data){
    //     // Transformar el array en el formato deseado
    //     $result = implode(', ', array_map(
    //         function ($key, $value) {
    //             $key = str_replace('_', ' ', $key); 
    //             return "$key: $value";
    //         },
    //         array_keys($data),
    //         $data
    //     ));
    //     // Imprimir el resultado
    //     return $result;
    // }

}