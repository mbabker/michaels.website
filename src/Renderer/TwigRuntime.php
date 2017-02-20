<?php

namespace BabDev\Website\Renderer;

use BabDev\Website\Application;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;

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
    public function isCurrentRoute(string $route): bool
    {
        return $this->app->get('uri.route') === $route;
    }

    /**
     * Render the pagination for this page.
     *
     * @param Pagerfanta $pagerfanta The pagination object
     *
     * @return string
     */
    public function renderPagination(Pagerfanta $pagerfanta): string
    {
        $routeGenerator = function ($page) {
            if ($page === 1) {
                return $this->getRouteUri('blog');
            }

            return $this->getRouteUri("blog/page/$page");
        };

        return (new TwitterBootstrap3View())->render($pagerfanta, $routeGenerator);
    }
}
