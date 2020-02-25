<?php declare(strict_types=1);

namespace BabDev\Website\Twig\Service;

use Joomla\Application\WebApplication;

final class RoutingService
{
    private WebApplication $app;

    public function __construct(WebApplication $app)
    {
        $this->app = $app;
    }

    public function getRequestUri(): string
    {
        return $this->app->get('uri.request', '');
    }

    public function getRouteUri(string $route): string
    {
        return $this->app->get('uri.base.full', '') . $route;
    }
}
