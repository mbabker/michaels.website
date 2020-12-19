<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

final class Kernel extends HttpKernel
{
    /**
     * @var class-string[]
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
    ];

    /**
     * @var array<string, class-string[]>
     */
    protected $middlewareGroups = [
        'web' => [
            \BabDev\ServerPushManager\Http\Middleware\ServerPush::class,
        ],
    ];
}
