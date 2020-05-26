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

    public function renderPagination(Pagerfanta $pagerfanta, string $view = 'bootstrap_4', array $options = []): string
    {
        $routeGenerator = function (int $page): string {
            if ($page === 1) {
                return $this->routing->getRouteUri('blog');
            }

            return $this->routing->getRouteUri("blog/page/$page");
        };

        return $this->viewFactory->get($view)->render($pagerfanta, $routeGenerator, $options);
    }
}
