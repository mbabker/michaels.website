<?php

namespace BabDev\Website\Service;

use BabDev\Website\EventListener\PreloadSubscriber;
use BabDev\Website\Manager\PreloadManager;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\Dispatcher;
use Joomla\Event\DispatcherInterface;

class EventProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->share(
            DispatcherInterface::class,
            function (Container $container): DispatcherInterface {
                $dispatcher = new Dispatcher;

                $dispatcher->addSubscriber(new PreloadSubscriber($container->get(PreloadManager::class)));

                return $dispatcher;
            }
        )
            ->alias(Dispatcher::class, DispatcherInterface::class);
    }
}
