<?php declare(strict_types=1);

namespace BabDev\Website\Service;

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
use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use Twig\Cache\CacheInterface;
use Twig\Cache\FilesystemCache;
use Twig\Cache\NullCache;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;
use Twig\Profiler\Profile;
use Twig\RuntimeLoader\ContainerRuntimeLoader;

final class TemplatingProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
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
                        'url' => new UrlPackage(
                            [$app->get('uri.media.full')],
                            new EmptyVersionStrategy(),
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
                return new TwigRenderer($container->get(Environment::class));
            },
            true
        )
            ->alias(TwigRenderer::class, RendererInterface::class);

        $container->share(
            CacheInterface::class,
            function (Container $container) use ($config, $templateDebug): CacheInterface {
                $templateCache = (bool) $config->get('template.cache', false);

                if ($templateDebug === false && $templateCache === true) {
                    return new FilesystemCache(JPATH_ROOT . '/cache/twig');
                }

                return new NullCache();
            },
            true
        );

        $container->share(
            Environment::class,
            function (Container $container) use ($config, $templateDebug): Environment {
                $environment = new Environment(
                    $container->get(LoaderInterface::class),
                    ['debug' => $templateDebug]
                );

                // Add the runtime loader
                $environment->addRuntimeLoader(new ContainerRuntimeLoader($container));

                // Set up the environment's caching service
                $environment->setCache($container->get(CacheInterface::class));

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
            DebugExtension::class,
            function (): DebugExtension {
                return new DebugExtension();
            }
        );

        $container->share(
            LoaderInterface::class,
            function (Container $container): LoaderInterface {
                return new FilesystemLoader([JPATH_TEMPLATES]);
            },
            true
        )
            ->alias(FilesystemLoader::class, LoaderInterface::class);

        $container->share(
            Profile::class,
            function (Container $container): Profile {
                return new Profile();
            },
            true
        );

        $container->share(
            TwigRuntime::class,
            function (Container $container): TwigRuntime {
                return new TwigRuntime(
                    $container->get(AbstractApplication::class),
                    $container->get(PreloadManager::class)
                );
            },
            true
        );

        $twigExtensions = [
            TwigExtension::class,
        ];

        if ($templateDebug) {
            $twigExtensions[] = DebugExtension::class;
        }

        $container->tag('twig.extension', $twigExtensions);
    }
}
