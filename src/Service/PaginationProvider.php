<?php declare(strict_types=1);

namespace BabDev\Website\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Pagerfanta\View\TwitterBootstrap4View;
use Pagerfanta\View\ViewFactory;
use Pagerfanta\View\ViewFactoryInterface;

final class PaginationProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->alias(ViewFactory::class, ViewFactoryInterface::class)
            ->share(
                ViewFactoryInterface::class,
                static function (): ViewFactoryInterface {
                    $factory = new ViewFactory;
                    $factory->set('bootstrap_4', new TwitterBootstrap4View());

                    return $factory;
                }
            )
        ;
    }
}
