<?php

namespace BabDev\Website\Renderer;

use BabDev\Website\Application;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Symfony\Component\Asset\Packages;

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
     * @var Packages
     */
    private $packages;

    /**
     * @param Application $app      The application object
     * @param Packages    $packages Packages object to look up asset paths
     */
    public function __construct(Application $app, Packages $packages)
    {
        $this->app      = $app;
        $this->packages = $packages;
    }

    /**
     * Retrieves the URI for a web asset.
     *
     * @param string $path        A public path
     * @param string $packageName The name of the asset package to use
     *
     * @return string
     */
    public function getAssetUri(string $path, $packageName = null): string
    {
        return $this->packages->getUrl($path, $packageName);
    }

    /**
     * Retrieves the current URI.
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        return $this->app->get('uri.request', '');
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
        return $this->app->get('uri.base.full', '') . $route;
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
