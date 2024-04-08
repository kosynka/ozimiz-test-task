<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class BaseResourceCollection extends ResourceCollection
{
    abstract protected function getResourceForPagination(): AnonymousResourceCollection;

    public function toArray($request)
    {
        return [
            'data' => $this->getResourceForPagination(),
            'meta' => [
                'current_page' => $this->currentPage(),
                'per_page' => (int) $this->perPage(),
                'page' => $this->currentPage(),
                'total' => $this->total(),

                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
                'last_page' => $this->lastPage(),
            ],
        ];
    }
}
