<?php declare(strict_types=1);

namespace BabDev\Website\Pagerfanta\RouteGenerator;

use BabDev\Website\Twig\Service\RoutingService;
use Pagerfanta\RouteGenerator\RouteGeneratorInterface;

final class RouteGenerator implements RouteGeneratorInterface
{
    private RoutingService $routing;

    public function __construct(RoutingService $routing)
    {
        $this->routing = $routing;
    }

    public function __invoke(int $page): string
    {
        if ($page === 1) {
            return $this->routing->getRouteUri('blog');
        }

        return $this->routing->getRouteUri("blog/page/$page");
    }
}
