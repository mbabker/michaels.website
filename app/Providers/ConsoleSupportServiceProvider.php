<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Providers\ComposerServiceProvider;
use Illuminate\Support\AggregateServiceProvider;

final class ConsoleSupportServiceProvider extends AggregateServiceProvider implements DeferrableProvider
{
    /**
     * The provider class names.
     *
     * @var class-string[]
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        ComposerServiceProvider::class,
    ];
}
