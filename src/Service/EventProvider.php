<?php

namespace BabDev\Website\Service;

use BabDev\Website\EventListener\ErrorSubscriber;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\Dispatcher;
use Joomla\Event\DispatcherInterface;
use Joomla\Renderer\RendererInterface;

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

        $container->share(
            ErrorSubscriber::class,
            function (Container $container): ErrorSubscriber {
                return new ErrorSubscriber(
                    $container->get(RendererInterface::class)
                );
            },
            true
        )
            ->tag('event.subscriber', [ErrorSubscriber::class]);
    }
}
