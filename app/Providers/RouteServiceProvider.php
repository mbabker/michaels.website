<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

final class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function (Router $router): void {
            $router->middleware('web')
                ->group($this->app->basePath('routes/web.php'));
        });
    }
}
