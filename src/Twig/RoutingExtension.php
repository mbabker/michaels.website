<?php declare(strict_types=1);

namespace BabDev\Website\Twig;

use BabDev\Website\Twig\Service\RoutingService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class RoutingExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('request_uri', [RoutingService::class, 'getRequestUri']),
            new TwigFunction('route', [RoutingService::class, 'getRouteUri']),
        ];
    }
}
