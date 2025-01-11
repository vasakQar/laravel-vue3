<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Resources\BlogResource;
use App\Http\Requests\Blog\IndexRequest;
use App\Http\Requests\Blog\StoreRequest;
use App\Http\Requests\Blog\UpdateRequest;
use App\Services\Contracts\BlogServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class BlogController extends Controller
{
    public function __construct(private readonly BlogServiceInterface $blogService)
    {
    }

    public function index(IndexRequest $request): AnonymousResourceCollection
    {
        $res = $this->blogService->getAll($request->validated());

        return BlogResource::collection($res);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $blog = $this->blogService->create($request->validated());

        return (new BlogResource($blog))
            ->response()
            ->setStatusCode(HttpResponse::HTTP_CREATED);
    }

    public function show(int $id): BlogResource
    {
        $blog = $this->blogService->getById($id);

        return new BlogResource($blog);
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $res = $this->blogService->update($id, $request->validated());

        if (!$res) {
            return response()->json(['message' => 'Unauthorized'], HttpResponse::HTTP_UNAUTHORIZED);
        }

        return response()->json(null, HttpResponse::HTTP_NO_CONTENT);
    }

    public function destroy(int $id): JsonResponse
    {
        $res = $this->blogService->delete($id);

        if (!$res) {
            return response()->json(['message' => 'Unauthorized'], HttpResponse::HTTP_UNAUTHORIZED);
        }

        return response()->json(null, HttpResponse::HTTP_NO_CONTENT);
    }
}
