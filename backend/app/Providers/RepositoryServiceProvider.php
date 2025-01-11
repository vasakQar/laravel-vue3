<?php

namespace App\Providers;

use App\Services\BlogService;
use App\Repositories\BlogRepository;
use Illuminate\Support\ServiceProvider;
use App\Services\Contracts\BlogServiceInterface;
use App\Repositories\Contracts\BlogRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Blog
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        $this->app->bind(BlogServiceInterface::class, BlogService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
