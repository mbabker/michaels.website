<?php

namespace App\Providers;

use App\Pagination\RoutableLengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerPagination();
    }

    private function registerPagination(): void
    {
        // Bind pagination to our local class
        $this->app->bind(LengthAwarePaginator::class, RoutableLengthAwarePaginator::class);

        // Add the route resolver
        RoutableLengthAwarePaginator::currentRouteResolver(function () {
            return $this->app['request']->route();
        });
    }
}
