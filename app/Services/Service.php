<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

abstract class Service
{
    protected $model;

    public function find(int $id, array $with = []): Model
    {
        return $this->model->with($with)->findOrFail($id);
    }
}
