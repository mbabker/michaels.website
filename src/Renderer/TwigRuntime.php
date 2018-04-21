<?php

namespace BabDev\Website\Renderer;

use Joomla\Application\AbstractApplication;
use Joomla\Preload\PreloadManager;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap3View;
use Symfony\Component\Asset\Packages;

final class TwigRuntime
{
    /**
     * @var AbstractApplication
     */
    private $app;

    /**
     * @var Packages
     */
    private $packages;

    /**
     * @var PreloadManager
     */
    private $preloadManager;

    public function __construct(AbstractApplication $app, Packages $packages, PreloadManager $preloadManager)
    {
        $this->app            = $app;
        $this->packages       = $packages;
        $this->preloadManager = $preloadManager;
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

    public function preloadAsset(string $uri, string $linkType = 'preload', array $attributes = []): string
    {
        $this->preloadManager->link($uri, $linkType, $attributes);

        return $uri;
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
