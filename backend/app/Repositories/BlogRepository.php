<?php

namespace App\Repositories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\BlogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlogRepository implements BlogRepositoryInterface
{
    protected Blog $model;

    public function __construct(Blog $model)
    {
        $this->model = $model;
    }

    public function getAll(int $perPage, string $sortBy, string $sortDirection): LengthAwarePaginator
    {
        return $this->model
            ->newQuery()
            ->orderBy($sortBy, $sortDirection)
            ->paginate($perPage);
    }

    public function getById(int $id): Blog|Builder
    {
        return $this->model->newQuery()
            ->findOrFail($id);
    }

    public function create(array $requestData): Model|Builder
    {
        return $this->model->newQuery()
            ->create($requestData);
    }

    public function update(Blog $blog, array $requestData): bool
    {
        return $blog->update($requestData);
    }

    public function delete(int $id): bool
    {
        return $this->model->newQuery()
            ->findOrFail($id)
            ->delete();
    }
}
