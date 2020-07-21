<?php declare(strict_types=1);

namespace BabDev\Website\Service;

use BabDev\Website\Pagerfanta\RouteGenerator\RouteGenerator;
use BabDev\Website\Pagerfanta\RouteGenerator\RouteGeneratorFactory;
use BabDev\Website\Twig\Service\RoutingService;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Pagerfanta\RouteGenerator\RouteGeneratorFactoryInterface;
use Pagerfanta\RouteGenerator\RouteGeneratorInterface;
use Pagerfanta\Twig\View\TwigView;
use Pagerfanta\View\ViewFactory;
use Pagerfanta\View\ViewFactoryInterface;
use Twig\Environment;

final class PaginationProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->alias(RouteGeneratorFactory::class, RouteGeneratorFactoryInterface::class)
            ->share(
                RouteGeneratorFactoryInterface::class,
                static function (Container $container): RouteGeneratorFactoryInterface {
                    return new RouteGeneratorFactory($container->get(RouteGeneratorInterface::class));
                }
            )
        ;

        $container->alias(RouteGenerator::class, RouteGeneratorInterface::class)
            ->share(
                RouteGeneratorInterface::class,
                static function (Container $container): RouteGeneratorInterface {
                    return new RouteGenerator($container->get(RoutingService::class));
                }
            )
        ;

        $container->alias(ViewFactory::class, ViewFactoryInterface::class)
            ->share(
                ViewFactoryInterface::class,
                static function (Container $container): ViewFactoryInterface {
                    $factory = new ViewFactory;
                    $factory->set('twig', $container->get(TwigView::class));

                    return $factory;
                }
            )
        ;

        $container->share(
            TwigView::class,
            static function (Container $container): TwigView {
                return new TwigView(
                    $container->get(Environment::class),
                    '@Pagerfanta/twitter_bootstrap4.html.twig'
                );
            }
        );
    }
}
