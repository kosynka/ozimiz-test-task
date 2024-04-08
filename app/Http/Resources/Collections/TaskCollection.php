<?php

namespace App\Http\Resources\Collections;

use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskCollection extends BaseResourceCollection
{
    protected function getResourceForPagination(): AnonymousResourceCollection
    {
        return TaskResource::collection($this->collection);
    }
}
