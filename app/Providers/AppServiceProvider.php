<?php

namespace App\Providers;

use App\Repositories\Contracts\PostContract;
use App\Repositories\Contracts\Sql\PostRepository;
use App\Repositories\Contracts\BookingContract;
use App\Repositories\Contracts\Sql\BookingRepository;
use App\Repositories\Contracts\ServiceContract;
use App\Repositories\Contracts\Sql\ServiceRepository;
use App\Services\BookingService;
use App\Services\ServiceService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PostContract::class, PostRepository::class);
        $this->app->bind(BookingContract::class, BookingRepository::class);
        $this->app->bind(ServiceContract::class, ServiceRepository::class);
        $this->app->bind(BookingService::class);
        $this->app->bind(ServiceService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
