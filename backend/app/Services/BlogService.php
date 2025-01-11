<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Contracts\BlogServiceInterface;
use App\Repositories\Contracts\BlogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlogService implements BlogServiceInterface
{
    protected BlogRepositoryInterface $blogRepository;

    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function getAll(array $requestData): LengthAwarePaginator
    {
        $perPage = $requestData['per_page'] ?? 20;
        $sortBy = $requestData['sort_by'] ?? 'created_at';
        $sortDirection = $requestData['sort_direction'] ?? 'desc';

        return $this->blogRepository->getAll($perPage, $sortBy, $sortDirection);
    }

    public function getById(int $id): Model|Builder
    {
        return $this->blogRepository->getById($id);
    }

    public function create(array $requestData): Model|Builder
    {
        $imagePath = null;
        if (isset($requestData['image']) && $requestData['image'] instanceof UploadedFile) {
            $imagePath = $requestData['image']->store('blogs', 'public');
        }

        $blog = [
            'user_id' => Auth::id(),
            'title' => $requestData['title'],
            'content' => $requestData['content'],
            'image' => $imagePath,
        ];

        return $this->blogRepository->create($blog);
    }

    public function update(int $id, array $requestData): bool
    {
        $blog = $this->blogRepository->getById($id);

        if ($blog->user_id !== auth()->id()) {
             return false;
        }

        if (isset($requestData['image']) && $requestData['image'] instanceof UploadedFile) {
            $imagePath = $requestData['image']->store('blogs', 'public');
            $requestData['image'] = $imagePath;
        }

        return $this->blogRepository->update($blog, $requestData);
    }

    public function delete(int $id): bool
    {
        $blog = $this->blogRepository->getById($id);

        if ($blog->user_id !== auth()->id()) {
            return false;
        }

        $deleted = $this->blogRepository->delete($id);
        if ($deleted) {
            // Delete image from storage
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
        }

        return $deleted;
    }
}
