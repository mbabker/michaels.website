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
use Joomla\Preload\PreloadManager;
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
        /** @var \Joomla\Registry\Registry $config */
        $config = $container->get('config');

        $templateDebug = (bool) $config->get('template.debug', false);

        $container->share(
            Packages::class,
            function (Container $container): Packages {
                /** @var AbstractApplication $app */
                $app = $container->get(AbstractApplication::class);

                $context = new ApplicationContext($app);

                $mediaPath = $app->get('uri.media.path', '/media/');

                $defaultPackage = new PathPackage($mediaPath, new EmptyVersionStrategy(), $context);

                return new Packages(
                    $defaultPackage,
                    [
                        'mix' => new MixPathPackage(
                            $defaultPackage,
                            $mediaPath,
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
            function (Container $container) use ($config, $templateDebug): \Twig_CacheInterface {
                $templateCache = (bool) $config->get('template.cache', false);

                if ($templateDebug === false && $templateCache === true) {
                    return new \Twig_Cache_Filesystem(JPATH_ROOT . '/cache/twig');
                }

                return new \Twig_Cache_Null();
            },
            true
        );

        $container->share(
            \Twig_Environment::class,
            function (Container $container) use ($config, $templateDebug): \Twig_Environment {
                $environment = new \Twig_Environment(
                    $container->get(\Twig_LoaderInterface::class),
                    ['debug' => $templateDebug]
                );

                // Add the runtime loader
                $environment->addRuntimeLoader(new \Twig_ContainerRuntimeLoader($container));

                // Set up the environment's caching service
                $environment->setCache($container->get(\Twig_CacheInterface::class));

                // Add the Twig extensions
                $environment->setExtensions($container->getTagged('twig.extension'));

                // Add a global tracking the debug states
                $environment->addGlobal('appDebug', (bool) $config->get('debug', false));
                $environment->addGlobal('layoutDebug', $templateDebug);

                return $environment;
            },
            true
        );

        $container->share(
            TwigExtension::class,
            function (): TwigExtension {
                return new TwigExtension();
            }
        );

        $container->share(
            \Twig_Extensions_Extension_Text::class,
            function (): \Twig_Extensions_Extension_Text {
                return new \Twig_Extensions_Extension_Text();
            }
        );

        $container->share(
            \Twig_Extension_Debug::class,
            function (): \Twig_Extension_Debug {
                return new \Twig_Extension_Debug();
            }
        );

        $container->share(
            \Twig_LoaderInterface::class,
            function (Container $container): \Twig_LoaderInterface {
                return new \Twig_Loader_Filesystem([JPATH_TEMPLATES]);
            },
            true
        )
            ->alias(\Twig_Loader_Filesystem::class, \Twig_LoaderInterface::class);

        $container->share(
            \Twig_Profiler_Profile::class,
            function (Container $container): \Twig_Profiler_Profile {
                return new \Twig_Profiler_Profile();
            },
            true
        );

        $container->share(
            TwigRuntime::class,
            function (Container $container): TwigRuntime {
                return new TwigRuntime($container->get(Application::class), $container->get(Packages::class), $container->get(PreloadManager::class));
            },
            true
        );

        $twigExtensions = [
            TwigExtension::class,
            \Twig_Extensions_Extension_Text::class,
        ];

        if ($templateDebug) {
            $twigExtensions[] = \Twig_Extension_Debug::class;
        }

        $container->tag('twig.extension', $twigExtensions);
    }
}
