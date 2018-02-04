<?php

namespace BabDev\Website\Service;

use BabDev\Website\DebugBar\JoomlaHttpDriver;
use DebugBar\Bridge\Twig\TimeableTwigExtensionProfiler;
use DebugBar\Bridge\TwigProfileCollector;
use DebugBar\DebugBar;
use DebugBar\StandardDebugBar;
use Joomla\Application\AbstractWebApplication;
use Joomla\DI\Container;
use Joomla\DI\Exception\DependencyResolutionException;
use Joomla\DI\ServiceProviderInterface;

final class DebugBarProvider implements ServiceProviderInterface
{
    public function register(Container $container)
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
                    new TwigProfileCollector($container->get(\Twig_Profiler_Profile::class), $container->get(\Twig_LoaderInterface::class))
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

        $container->extend(
            \Twig_Environment::class,
            function (\Twig_Environment $twig, Container $container): \Twig_Environment {
                $twig->addExtension(
                    new TimeableTwigExtensionProfiler(
                        $container->get(\Twig_Profiler_Profile::class),
                        $container->get(DebugBar::class)->getCollector('time')
                    )
                );

                return $twig;
            }
        );
    }
}
