<?php

namespace App\Providers;

use App\Pagination\RoutableLengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerPagination();
    }

    private function registerPagination(): void
    {
        // Bind pagination to our local class
        $this->app->bind(LengthAwarePaginator::class, RoutableLengthAwarePaginator::class);

        // Change the current page resolver to be aware of the route parameters
        AbstractPaginator::currentPageResolver(function ($pageName = 'page') {
            $route = $this->app['request']->route();

            if ($page = $route->parameter($pageName)) {
                return $page;
            }

            $page = $this->app['request']->input($pageName);

            if (\filter_var($page, \FILTER_VALIDATE_INT) !== false && (int) $page >= 1) {
                return (int) $page;
            }

            return 1;
        });

        // Add the route resolver
        RoutableLengthAwarePaginator::currentRouteResolver(function () {
            return $this->app['request']->route();
        });
    }
}
