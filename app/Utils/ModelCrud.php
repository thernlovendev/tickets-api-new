<?php

namespace App\Utils;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Model;
class ModelCrud
{
    static function deleteUpdateOrCreate(Relation $relation, array $items)
    {

        $relation->get()->each(function($model) use ($items) {
            foreach ($items as $item) {
                if ($model->id === ($item['id'] ?? null)) {
                    $model->fill($item)->save();
                    return;
                }
            }

            return $model->delete();
        });

        foreach ($items as $item) {
            $item['id'] = isset($item['id']) ? $item['id'] : null;
            if (!$item['id'])
                $relation->create($item);
        };

        return true;
    }
}
