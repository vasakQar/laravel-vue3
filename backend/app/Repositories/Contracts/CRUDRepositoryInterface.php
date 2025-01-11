<?php

namespace App\Repositories\Contracts;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CRUDRepositoryInterface
{
    public function getAll(int $perPage, string $sortBy, string $sortDirection): LengthAwarePaginator;

    public function getById(int $id): Blog|Builder;

    public function create(array $requestData): Model|Builder;

    public function update(Blog $blog, array $requestData): bool;

    public function delete(int $id): bool;
}
