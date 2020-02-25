<?php declare(strict_types=1);

namespace BabDev\Website\Twig\Service;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\ViewFactoryInterface;

final class PaginationService
{
    private ViewFactoryInterface $viewFactory;

    private RoutingService $routing;

    public function __construct(ViewFactoryInterface $viewFactory, RoutingService $routing)
    {
        $this->viewFactory = $viewFactory;
        $this->routing     = $routing;
    }

    public function renderPagination(Pagerfanta $pagerfanta): string
    {
        $routeGenerator = function ($page): string {
            if ($page === 1) {
                return $this->routing->getRouteUri('blog');
            }

            return $this->routing->getRouteUri("blog/page/$page");
        };

        return $this->viewFactory->get('bootstrap_4')->render($pagerfanta, $routeGenerator);
    }
}
