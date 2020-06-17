<?php declare(strict_types=1);

namespace BabDev\Website\Service;

use BabDev\Website\Asset\Context\ApplicationContext;
use BabDev\Website\Asset\MixPathPackage;
use BabDev\Website\Twig\AppExtension;
use BabDev\Website\Twig\AssetExtension;
use BabDev\Website\Twig\RoutingExtension;
use BabDev\Website\Twig\Service\AssetService;
use BabDev\Website\Twig\Service\RoutingService;
use Joomla\Application\WebApplication;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Preload\PreloadManager;
use Joomla\Renderer\RendererInterface;
use Joomla\Renderer\TwigRenderer;
use Pagerfanta\RouteGenerator\RouteGeneratorFactoryInterface;
use Pagerfanta\Twig\PagerfantaExtension;
use Pagerfanta\Twig\PagerfantaRuntime;
use Pagerfanta\View\ViewFactoryInterface;
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
                /** @var WebApplication $app */
                $app = $container->get(WebApplication::class);

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
            }
        );

        $container->alias(TwigRenderer::class, RendererInterface::class)
            ->share(
                RendererInterface::class,
                static function (Container $container): RendererInterface {
                    return new TwigRenderer($container->get(Environment::class));
                }
            )
        ;

        $container->share(
            CacheInterface::class,
            static function () use ($config, $templateDebug): CacheInterface {
                $templateCache = (bool) $config->get('template.cache', false);

                if ($templateDebug === false && $templateCache === true) {
                    return new FilesystemCache(JPATH_ROOT . '/cache/twig');
                }

                return new NullCache();
            }
        );

        $container->share(
            Environment::class,
            static function (Container $container) use ($config, $templateDebug): Environment {
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
            }
        );

        $container->share(
            AssetExtension::class,
            static function (): AssetExtension {
                return new AssetExtension();
            }
        );

        $container->share(
            PagerfantaExtension::class,
            static function (): PagerfantaExtension {
                return new PagerfantaExtension();
            }
        );

        $container->share(
            RoutingExtension::class,
            static function (): RoutingExtension {
                return new RoutingExtension();
            }
        );

        $container->share(
            AppExtension::class,
            static function (): AppExtension {
                return new AppExtension();
            }
        );

        $container->share(
            DebugExtension::class,
            static function (): DebugExtension {
                return new DebugExtension();
            }
        );

        $container->alias(FilesystemLoader::class, LoaderInterface::class)
            ->share(
                LoaderInterface::class,
                static function (): LoaderInterface {
                    $loader = new FilesystemLoader([JPATH_TEMPLATES]);
                    $loader->addPath(JPATH_ROOT . '/vendor/pagerfanta/pagerfanta/templates', 'Pagerfanta');

                    return $loader;
                }
            )
        ;

        $container->share(
            Profile::class,
            static function (): Profile {
                return new Profile();
            }
        );

        $container->share(
            AssetService::class,
            static function (Container $container): AssetService {
                return new AssetService(
                    $container->get(Packages::class),
                    $container->get(PreloadManager::class)
                );
            },
            true
        );

        $container->share(
            PagerfantaRuntime::class,
            static function (Container $container): PagerfantaRuntime {
                return new PagerfantaRuntime(
                    'twig',
                    $container->get(ViewFactoryInterface::class),
                    $container->get(RouteGeneratorFactoryInterface::class)
                );
            },
            true
        );

        $container->share(
            RoutingService::class,
            static function (Container $container): RoutingService {
                return new RoutingService(
                    $container->get(WebApplication::class)
                );
            },
            true
        );

        $twigExtensions = [
            AppExtension::class,
            AssetExtension::class,
            PagerfantaExtension::class,
            RoutingExtension::class,
        ];

        if ($templateDebug) {
            $twigExtensions[] = DebugExtension::class;
        }

        $container->tag('twig.extension', $twigExtensions);
    }
}
