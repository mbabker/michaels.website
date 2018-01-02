<?php

namespace BabDev\Website\Service;

use BabDev\Website\DebugBar\Twig\TraceableTwigEnvironment;
use BabDev\Website\DebugBar\Twig\TwigCollector;
use DebugBar\DebugBar;
use DebugBar\StandardDebugBar;
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
                $debugBar->addCollector(new TwigCollector($container->get(\Twig_Environment::class)));

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

        $container->extend(
            \Twig_Environment::class,
            function (\Twig_Environment $twig): TraceableTwigEnvironment {
                return new TraceableTwigEnvironment($twig);
            }
        );
    }
}
