<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TaskService extends Service
{
    public function __construct()
    {
        $this->model = new Task;
    }

    public function index(array $params): Collection|Model|LengthAwarePaginator
    {
        $query = $this->model->where('user_id', auth()->user()->id);

        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['sort_by'])) {
            $this->applySorting($query, $params);
        }

        if (isset($params['page']) || isset($params['per_page'])) {
            $data = $this->applyPagination($query, $params);
        } else {
            $data = $query->get();
        }

        return $data;
    }

    public function store(array $data): Model
    {
        $data['user_id'] = auth()->user()->id;

        if (request()->file('image')) {
            $data['image_url'] = $this->storeFile(request()->file('image'));
        }

        return $this->model->create($data);
    }

    public function update(Model $model, array $data): bool
    {
        if (request()->file('image')) {
            $data['image_url'] = $this->storeFile(request()->file('image'));

            unset($data['image']);
        }

        $model->update($data);

        return $model->save();
    }
}
