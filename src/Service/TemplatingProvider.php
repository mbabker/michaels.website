<?php

namespace BabDev\Website\Service;

use BabDev\Website\Application;
use BabDev\Website\Renderer\TwigExtension;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Renderer\RendererInterface;
use Joomla\Renderer\TwigRenderer;

/**
 * Templating service provider.
 */
class TemplatingProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $container->alias('renderer', RendererInterface::class)
            ->alias(TwigRenderer::class, RendererInterface::class)
            ->set(
                RendererInterface::class,
                function (Container $container): RendererInterface {
                    /** @var \Joomla\Registry\Registry $config */
                    $config = $container->get('config');

                    $templatePaths = $config->get('template.paths', [JPATH_TEMPLATES]);
                    $templateDebug = (bool) $config->get('template.debug', false);
                    $templateCache = $config->get('template.cache', false);

                    // Instantiate the Twig environment
                    $environment = new \Twig_Environment(
                        new \Twig_Loader_Filesystem($templatePaths),
                        ['debug' => $templateDebug]
                    );

                    // Add a global tracking the debug state
                    $environment->addGlobal('layoutDebug', $templateDebug);

                    // Set up the environment's caching mechanism
                    $cacheService = new \Twig_Cache_Null;

                    if ($templateDebug === false && $templateCache !== false) {
                        // Check for a custom cache path otherwise use the default
                        $cachePath = $templateCache === true ? JPATH_ROOT . '/cache/twig' : $templateCache;

                        $cacheService = new \Twig_Cache_Filesystem($cachePath);
                    }

                    $environment->setCache($cacheService);

                    // Add our Twig extension
                    $environment->addExtension(new TwigExtension($container->get(Application::class)));

                    // Add Twig's Text extension
                    $environment->addExtension(new \Twig_Extensions_Extension_Text());

                    // Add the debug extension if enabled
                    if ($templateDebug) {
                        $environment->addExtension(new \Twig_Extension_Debug);
                    }

                    return new TwigRenderer($environment);
                },
                true,
                true
            );
    }
}
