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
     * @param Application $app
     * @param Packages    $packages
     */
    public function __construct(Application $app, Packages $packages)
    {
        $this->app      = $app;
        $this->packages = $packages;
    }

    public function getAssetUri(string $path, ?string $packageName = null): string
    {
        return $this->packages->getUrl($path, $packageName);
    }

    public function getRequestUri(): string
    {
        return $this->app->get('uri.request', '');
    }

    public function getRouteUri(string $route): string
    {
        return $this->app->get('uri.base.full', '') . $route;
    }

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
