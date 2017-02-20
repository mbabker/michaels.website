<?php

namespace BabDev\Website\Renderer;

use BabDev\Website\Application;

/**
 * Twig runtime class.
 */
class TwigRuntime
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app The application object
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Retrieves the URI for a web asset.
     *
     * @param string $asset The asset to process
     *
     * @return string
     */
    public function getAssetUri(string $asset): string
    {
        return $this->app->get('uri.media.full') . $asset;
    }

    /**
     * Retrieves the current URI.
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        return $this->app->get('uri.request');
    }

    /**
     * Retrieves the URI for a route.
     *
     * @param string $route The route to process
     *
     * @return string
     */
    public function getRouteUri(string $route): string
    {
        return $this->app->get('uri.base.full') . $route;
    }

    /**
     * Check if a route is the route for the current page.
     *
     * @param string $route The route to process
     *
     * @return bool
     */
    public function isCurrentRoute(string $route): string
    {
        return $this->app->get('uri.route') === $route;
    }
}
