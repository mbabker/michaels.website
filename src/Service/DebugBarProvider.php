<?php declare(strict_types=1);

namespace BabDev\Website\Service;

use BabDev\Website\DebugBar\JoomlaHttpDriver;
use BabDev\Website\Event\DebugDispatcher;
use BabDev\Website\EventListener\DebugSubscriber;
use DebugBar\Bridge\Twig\TimeableTwigExtensionProfiler;
use DebugBar\Bridge\TwigProfileCollector;
use DebugBar\DebugBar;
use DebugBar\StandardDebugBar;
use Joomla\Application\AbstractWebApplication;
use Joomla\DI\Container;
use Joomla\DI\Exception\DependencyResolutionException;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Twig\Loader\LoaderInterface;
use Twig\Profiler\Profile;

final class DebugBarProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->share(
            StandardDebugBar::class,
            function (Container $container): DebugBar {
                if (!class_exists(StandardDebugBar::class)) {
                    throw new DependencyResolutionException(
                        sprintf('The %s class is not loaded.', StandardDebugBar::class)
                    );
                }

                $debugBar = new StandardDebugBar;

                // Add collectors
                $debugBar->addCollector(
                    new TwigProfileCollector($container->get(Profile::class), $container->get(LoaderInterface::class))
                );

                // Ensure the assets are dumped
                $renderer = $debugBar->getJavascriptRenderer();
                $renderer->disableVendor('fontawesome');
                $renderer->disableVendor('jquery');
                $renderer->dumpCssAssets(JPATH_ROOT . '/www/media/css/debugbar.css');
                $renderer->dumpJsAssets(JPATH_ROOT . '/www/media/js/debugbar.js');

                return $debugBar;
            },
            true
        )
            ->alias(DebugBar::class, StandardDebugBar::class);

        $container->share(
            JoomlaHttpDriver::class,
            function (Container $container): JoomlaHttpDriver {
                return new JoomlaHttpDriver($container->get(AbstractWebApplication::class));
            },
            true
        );

        $container->share(
            TimeableTwigExtensionProfiler::class,
            function (Container $container): TimeableTwigExtensionProfiler {
                return new TimeableTwigExtensionProfiler(
                    $container->get(Profile::class),
                    $container->get(DebugBar::class)->getCollector('time')
                );
            }
        )
            ->tag('twig.extension', [TimeableTwigExtensionProfiler::class]);

        $container->extend(
            DispatcherInterface::class,
            function (DispatcherInterface $dispatcher, Container $container): DispatcherInterface {
                $dispatcher = new DebugDispatcher($dispatcher, $container->get(DebugBar::class));

                $dispatcher->addSubscriber(new DebugSubscriber($container->get(DebugBar::class)));

                return $dispatcher;
            }
        );
    }
}
