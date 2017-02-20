<?php

namespace BabDev\Website\Service;

use BabDev\Website\Application;
use BabDev\Website\Renderer\TwigExtension;
use BabDev\Website\Renderer\TwigRuntime;
use BabDev\Website\Renderer\TwigRuntimeLoader;
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
        $container->alias(RendererInterface::class, 'renderer')
            ->alias(TwigRenderer::class, 'renderer')
            ->share('renderer', [$this, 'getRendererService'], true);

        $container->alias(\Twig_CacheInterface::class, 'twig.cache')
            ->share('twig.cache', [$this, 'getTwigCacheService'], true);

        $container->alias(\Twig_Environment::class, 'twig.environment')
            ->share('twig.environment', [$this, 'getTwigEnvironmentService'], true);

        $container->alias(TwigExtension::class, 'twig.extension.app')
            ->share('twig.extension.app', [$this, 'getTwigExtensionAppService'], true);

        $container->alias(\Twig_Extension_Debug::class, 'twig.extension.debug')
            ->share('twig.extension.debug', [$this, 'getTwigExtensionDebugService'], true);

        $container->alias(\Twig_Extensions_Extension_Text::class, 'twig.extension.text')
            ->share('twig.extension.text', [$this, 'getTwigExtensionTextService'], true);

        $container->share('twig.extensions', [$this, 'getTwigExtensionsService'], true);

        $container->alias(\Twig_LoaderInterface::class, 'twig.loader')
            ->share('twig.loader', [$this, 'getTwigLoaderService'], true);

        $container->alias(TwigRuntime::class, 'twig.runtime')
            ->share('twig.runtime', [$this, 'getTwigRuntimeService'], true);

        $container->alias(TwigRuntimeLoader::class, 'twig.runtime.loader')
            ->share('twig.runtime.loader', [$this, 'getTwigRuntimeLoaderService'], true);
    }

    /**
     * Get the `renderer` service.
     *
     * @param Container $container The DI container.
     *
     * @return RendererInterface
     */
    public function getRendererService(Container $container): RendererInterface
    {
        return new TwigRenderer($container->get('twig.environment'));
    }

    /**
     * Get the `twig.cache` service.
     *
     * @param Container $container The DI container.
     *
     * @return \Twig_CacheInterface
     */
    public function getTwigCacheService(Container $container): \Twig_CacheInterface
    {
        /** @var \Joomla\Registry\Registry $config */
        $config = $container->get('config');

        $templateDebug = (bool) $config->get('template.debug', false);
        $templateCache = $config->get('template.cache', false);

        if ($templateDebug === false && $templateCache !== false) {
            // Check for a custom cache path otherwise use the default
            $cachePath = $templateCache === true ? JPATH_ROOT . '/cache/twig' : $templateCache;

            $cacheService = new \Twig_Cache_Filesystem($cachePath);
        }

        return new \Twig_Cache_Null();
    }

    /**
     * Get the `twig.environment` service.
     *
     * @param Container $container The DI container.
     *
     * @return \Twig_Environment
     */
    public function getTwigEnvironmentService(Container $container): \Twig_Environment
    {
        /** @var \Joomla\Registry\Registry $config */
        $config = $container->get('config');

        $templateDebug = (bool) $config->get('template.debug', false);

        $environment = new \Twig_Environment(
            $container->get('twig.loader'),
            ['debug' => $templateDebug]
        );

        // Add the runtime loader
        $environment->addRuntimeLoader($container->get('twig.runtime.loader'));

        // Set up the environment's caching service
        $environment->setCache($container->get('twig.cache'));

        // Add the Twig extensions
        $environment->setExtensions($container->get('twig.extensions'));

        // Add a global tracking the debug state
        $environment->addGlobal('layoutDebug', $templateDebug);

        return $environment;
    }

    /**
     * Get the `twig.extension.app` service.
     *
     * @param Container $container The DI container.
     *
     * @return TwigExtension
     */
    public function getTwigExtensionAppService(Container $container): TwigExtension
    {
        return new TwigExtension();
    }

    /**
     * Get the `twig.extension.debug` service.
     *
     * @param Container $container The DI container.
     *
     * @return \Twig_Extension_Debug
     */
    public function getTwigExtensionDebugService(Container $container): \Twig_Extension_Debug
    {
        return new \Twig_Extension_Debug();
    }

    /**
     * Get the `twig.extension.text` service.
     *
     * @param Container $container The DI container.
     *
     * @return \Twig_Extensions_Extension_Text
     */
    public function getTwigExtensionTextService(Container $container): \Twig_Extensions_Extension_Text
    {
        return new \Twig_Extensions_Extension_Text();
    }

    /**
     * Get the `twig.extensions` service.
     *
     * @param Container $container The DI container.
     *
     * @return \Twig_ExtensionInterface[]
     */
    public function getTwigExtensionsService(Container $container): array
    {
        /** @var \Joomla\Registry\Registry $config */
        $config = $container->get('config');

        $templateDebug = (bool) $config->get('template.debug', false);

        $extensions = [
            $container->get('twig.extension.app'),
            $container->get('twig.extension.text'),
        ];

        if ($templateDebug) {
            $extensions[] = $container->get('twig.extension.debug');
        }

        return $extensions;
    }

    /**
     * Get the `twig.loader` service.
     *
     * @param Container $container The DI container.
     *
     * @return \Twig_LoaderInterface
     */
    public function getTwigLoaderService(Container $container): \Twig_LoaderInterface
    {
        /** @var \Joomla\Registry\Registry $config */
        $config = $container->get('config');

        $templatePaths = $config->get('template.paths', [JPATH_TEMPLATES]);

        return new \Twig_Loader_Filesystem($templatePaths);
    }

    /**
     * Get the `twig.runtime` service.
     *
     * @param Container $container The DI container.
     *
     * @return TwigRuntime
     */
    public function getTwigRuntimeService(Container $container): TwigRuntime
    {
        return new TwigRuntime($container->get(Application::class));
    }

    /**
     * Get the `twig.runtime.loader` service.
     *
     * @param Container $container The DI container.
     *
     * @return TwigRuntimeLoader
     */
    public function getTwigRuntimeLoaderService(Container $container): TwigRuntimeLoader
    {
        return new TwigRuntimeLoader($container);
    }
}
