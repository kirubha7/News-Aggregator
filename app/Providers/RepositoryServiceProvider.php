<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\{PreDefinedDataRepository,ArticleRepository};
use App\Repositories\Contracts\{PreDefinedDataRepositoryInterface,ArticleRepositoryInterface};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PreDefinedDataRepositoryInterface::class, PreDefinedDataRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
