<?php

namespace BabDev\Website\Service;

use BabDev\Website\DebugBar\Twig\TraceableTwigEnvironment;
use BabDev\Website\DebugBar\Twig\TwigCollector;
use DebugBar\DebugBar;
use DebugBar\StandardDebugBar;
use Joomla\DI\Container;
use Joomla\DI\Exception\DependencyResolutionException;
use Joomla\DI\ServiceProviderInterface;

/**
 * Debug bar service provider.
 */
final class DebugBarProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $container->alias(DebugBar::class, 'debug.bar')
            ->alias(StandardDebugBar::class, 'debug.bar')
            ->share('debug.bar', [$this, 'getDebugBarService'], true);

        $container->alias(TwigCollector::class, 'debug.collector.twig')
            ->share('debug.collector.twig', [$this, 'getDebugCollectorTwigService'], true);

        $container->extend(
            'twig.environment',
            function (\Twig_Environment $twig, Container $container): TraceableTwigEnvironment {
                return new TraceableTwigEnvironment($twig);
            }
        );
    }

    public function getDebugBarService(Container $container): DebugBar
    {
        if (!class_exists(StandardDebugBar::class)) {
            throw new DependencyResolutionException(sprintf('The %s class is not loaded.', StandardDebugBar::class));
        }

        $debugBar = new StandardDebugBar;

        // Add collectors
        $debugBar->addCollector($container->get('debug.collector.twig'));

        // Ensure the assets are dumped
        $renderer = $debugBar->getJavascriptRenderer();
        $renderer->disableVendor('fontawesome');
        $renderer->disableVendor('jquery');
        $renderer->dumpCssAssets(JPATH_ROOT . '/www/media/css/debugbar.css');
        $renderer->dumpJsAssets(JPATH_ROOT . '/www/media/js/debugbar.js');

        return $debugBar;
    }

    public function getDebugCollectorTwigService(Container $container): TwigCollector
    {
        return new TwigCollector($container->get('twig.environment'));
    }
}
