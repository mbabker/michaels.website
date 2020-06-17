<?php declare(strict_types=1);

namespace BabDev\Website\Pagerfanta\RouteGenerator;

use Pagerfanta\RouteGenerator\RouteGeneratorFactoryInterface;
use Pagerfanta\RouteGenerator\RouteGeneratorInterface;

final class RouteGeneratorFactory implements RouteGeneratorFactoryInterface
{
    private RouteGeneratorInterface $routeGenerator;

    public function __construct(RouteGeneratorInterface $routeGenerator)
    {
        $this->routeGenerator = $routeGenerator;
    }

    public function create(array $options = []): RouteGeneratorInterface
    {
        return $this->routeGenerator;
    }
}
