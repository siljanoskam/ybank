<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait ModelOperations
{
    protected function getAllModelItems(Model $model)
    {
        return $model->all();
    }

    protected function storeModelItem(Model $model, $data)
    {
        return $model->create($data);
    }

    protected function findModelItem(Model $model, $id)
    {
        return $model->findOrFail($id);
    }

    protected function updateModelItem(Model $model, $id, $data)
    {
        $item = $this->findModelItem($model, $id);

        $item->update($data);

        return $item;
    }

    protected function destroyModelItem(Model $model, $id)
    {
        $item = $this->findModelItem($model, $id);

        return $item->delete();
    }
}
