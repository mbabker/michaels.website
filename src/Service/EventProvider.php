<?php

namespace BabDev\Website\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\Dispatcher;
use Joomla\Event\DispatcherInterface;

final class EventProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        // This service cannot be protected as it is decorated when the debug bar is available
        $container->share(
            DispatcherInterface::class,
            function (Container $container): DispatcherInterface {
                $dispatcher = new Dispatcher;

                foreach ($container->getTagged('event.subscriber') as $subscriber) {
                    $dispatcher->addSubscriber($subscriber);
                }

                return $dispatcher;
            }
        )
            ->alias(Dispatcher::class, DispatcherInterface::class);
    }
}
