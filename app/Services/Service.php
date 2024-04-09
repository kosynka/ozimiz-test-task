<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

abstract class Service
{
    protected Model $model;

    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    protected function applyPagination(Builder $query, array $params): LengthAwarePaginator
    {
        return $query->paginate(
            perPage: $params['per_page'] ?? 15,
            page: $params['page'] ?? 1,
        );
    }

    protected function applySorting(Builder &$query, array $params): Builder
    {
        $sort = 'asc';
        if ((isset($params['sort']) && $params['sort'] == 'desc') || (isset($params['desc']) && $params['desc'] == 1)) {
            $sort = 'desc';
        }

        $sortBy = 'id';
        if (isset($params['sort_by'])) {
            $sortBy = $params['sort_by'];
        }

        return $query->orderBy($sortBy, $sort);
    }

    protected function storeFile($file): string
    {
        $filePath = $this->generateFileName();

        $file->storeAs('', $filePath, ['disk' => 'public']);

        return Storage::disk('public')->url($filePath);
    }

    private function generateFileName(): string
    {
        return uniqid('task_image_') . '_' . now()->format('Y_m_d_h_i_s') . '.png';
    }
}
