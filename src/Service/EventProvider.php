<?php declare(strict_types=1);

namespace BabDev\Website\Service;

use BabDev\Website\EventListener\ErrorSubscriber;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\Dispatcher;
use Joomla\Event\DispatcherInterface;
use Joomla\Renderer\RendererInterface;

final class EventProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        // This service cannot be protected as it is decorated when the debug bar is available
        $container->alias(Dispatcher::class, DispatcherInterface::class)
            ->share(
                DispatcherInterface::class,
                static function (Container $container): DispatcherInterface {
                    $dispatcher = new Dispatcher;

                    foreach ($container->getTagged('event.subscriber') as $subscriber) {
                        $dispatcher->addSubscriber($subscriber);
                    }

                    return $dispatcher;
                }
            )
        ;

        $container->share(
            ErrorSubscriber::class,
            static function (Container $container): ErrorSubscriber {
                return new ErrorSubscriber(
                    $container->get(RendererInterface::class)
                );
            }
        );

        $container->tag('event.subscriber', [ErrorSubscriber::class]);
    }
}
