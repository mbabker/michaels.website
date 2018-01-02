<?php

namespace BabDev\Website\Service;

use BabDev\Website\Application;
use BabDev\Website\Asset\MixPathPackage;
use BabDev\Website\Renderer\ApplicationContext;
use BabDev\Website\Renderer\TwigExtension;
use BabDev\Website\Renderer\TwigRuntime;
use Joomla\Application\AbstractApplication;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Renderer\RendererInterface;
use Joomla\Renderer\TwigRenderer;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;

final class TemplatingProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->share(
            Packages::class,
            function (Container $container): Packages {
                /** @var AbstractApplication $app */
                $app = $container->get(AbstractApplication::class);

                $context = new ApplicationContext($app);

                $defaultPackage = new PathPackage('/media/', new EmptyVersionStrategy(), $context);

                return new Packages(
                    $defaultPackage,
                    [
                        'mix' => new MixPathPackage(
                            $defaultPackage,
                            '/media/',
                            new JsonManifestVersionStrategy(JPATH_ROOT . '/www/media/mix-manifest.json'),
                            $context
                        ),
                    ]
                );
            },
            true
        );

        $container->share(
            RendererInterface::class,
            function (Container $container): RendererInterface {
                return new TwigRenderer($container->get(\Twig_Environment::class));
            },
            true
        )
            ->alias(TwigRenderer::class, RendererInterface::class);

        $container->share(
            \Twig_CacheInterface::class,
            function (Container $container): \Twig_CacheInterface {
                /** @var \Joomla\Registry\Registry $config */
                $config = $container->get('config');

                $templateDebug = (bool) $config->get('template.debug', false);
                $templateCache = $config->get('template.cache', false);

                if ($templateDebug === false && $templateCache !== false) {
                    // Check for a custom cache path otherwise use the default
                    $cachePath = $templateCache === true ? JPATH_ROOT . '/cache/twig' : $templateCache;

                    return new \Twig_Cache_Filesystem($cachePath);
                }

                return new \Twig_Cache_Null();
            },
            true
        );

        // This service cannot be protected as it is decorated when the debug bar is available
        $container->share(
            \Twig_Environment::class,
            function (Container $container): \Twig_Environment {
                /** @var \Joomla\Registry\Registry $config */
                $config = $container->get('config');

                $templateDebug = (bool) $config->get('template.debug', false);

                $templatePaths = $config->get('template.paths', [JPATH_TEMPLATES]);

                $environment = new \Twig_Environment(
                    new \Twig_Loader_Filesystem($templatePaths),
                    ['debug' => $templateDebug]
                );

                // Add the runtime loader
                $environment->addRuntimeLoader(new \Twig_ContainerRuntimeLoader($container));

                // Set up the environment's caching service
                $environment->setCache($container->get(\Twig_CacheInterface::class));

                // Add the Twig extensions
                $environment->addExtension(new TwigExtension());
                $environment->addExtension(new \Twig_Extensions_Extension_Text());

                if ($templateDebug) {
                    $environment->addExtension(new \Twig_Extension_Debug());
                }

                // Add a global tracking the debug states
                $environment->addGlobal('appDebug', $config->get('debug', false));
                $environment->addGlobal('layoutDebug', $templateDebug);

                return $environment;
            }
        );

        $container->share(
            TwigRuntime::class,
            function (Container $container): TwigRuntime {
                return new TwigRuntime($container->get(Application::class), $container->get(Packages::class));
            },
            true
        );
    }
}
