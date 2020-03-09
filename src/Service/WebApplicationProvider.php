<?php declare(strict_types=1);

namespace BabDev\Website\Service;

use BabDev\Website\Controller\BlogController;
use BabDev\Website\Controller\BlogPostController;
use BabDev\Website\Controller\HomepageController;
use BabDev\Website\Controller\PageController;
use BabDev\Website\Controller\SitemapController;
use BabDev\Website\Model\BlogPostModel;
use Joomla\Application\AbstractApplication;
use Joomla\Application\AbstractWebApplication;
use Joomla\Application\ApplicationInterface;
use Joomla\Application\Controller\ContainerControllerResolver;
use Joomla\Application\Controller\ControllerResolverInterface;
use Joomla\Application\WebApplication;
use Joomla\Application\WebApplicationInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\Renderer\RendererInterface;
use Joomla\Router\Router;
use Joomla\Router\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class WebApplicationProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->alias(WebApplication::class, WebApplicationInterface::class)
            ->alias(AbstractWebApplication::class, WebApplicationInterface::class)
            ->alias(AbstractApplication::class, WebApplicationInterface::class)
            ->alias(ApplicationInterface::class, WebApplicationInterface::class)
            ->share(
                WebApplicationInterface::class,
                static function (Container $container): WebApplicationInterface {
                    /** @var Registry $config */
                    $config = $container->get('config');

                    $application = new WebApplication(
                        $container->get(ControllerResolverInterface::class),
                        $container->get(RouterInterface::class),
                        $container->get(Input::class),
                        $config
                    );

                    // Inject extra services
                    $application->setDispatcher($container->get(DispatcherInterface::class));

                    return $application;
                }
            )
        ;

        $container->alias(ContainerControllerResolver::class, ControllerResolverInterface::class)
            ->share(
                ControllerResolverInterface::class,
                static function (Container $container): ControllerResolverInterface {
                    return new ContainerControllerResolver($container);
                }
            )
        ;

        $container->share(
            Input::class,
            static function (): Input {
                return new Input($_REQUEST);
            }
        );

        $container->alias(Router::class, RouterInterface::class)
            ->share(
                RouterInterface::class,
                static function (): RouterInterface {
                    return (new Router())
                        ->get('/', HomepageController::class)
                        ->get('/blog', BlogController::class)
                        ->get('/blog/page/:page', BlogController::class, ['page' => '(\d+)'])
                        ->get('/blog/:alias', BlogPostController::class)
                        ->get('/sitemap.xml', SitemapController::class, [], ['_format' => 'xml'])
                        ->get('/:view', PageController::class);
                }
            )
        ;

        $container->share(
            BlogController::class,
            static function (Container $container): BlogController {
                return new BlogController(
                    $container->get(RendererInterface::class),
                    $container->get(BlogPostModel::class),
                    $container->get(WebApplicationInterface::class)
                );
            }
        );

        $container->share(
            BlogPostController::class,
            static function (Container $container): BlogPostController {
                return new BlogPostController(
                    $container->get(RendererInterface::class),
                    $container->get(BlogPostModel::class),
                    $container->get(WebApplicationInterface::class)
                );
            }
        );

        $container->share(
            HomepageController::class,
            static function (Container $container): HomepageController {
                return new HomepageController(
                    $container->get(RendererInterface::class),
                    $container->get(BlogPostModel::class),
                    $container->get(WebApplicationInterface::class)
                );
            }
        );

        $container->share(
            PageController::class,
            static function (Container $container): PageController {
                return new PageController(
                    $container->get(RendererInterface::class),
                    $container->get(WebApplicationInterface::class)
                );
            }
        );

        $container->share(
            SitemapController::class,
            static function (Container $container): SitemapController {
                return new SitemapController(
                    $container->get(RendererInterface::class),
                    $container->get(BlogPostModel::class),
                    $container->get(WebApplicationInterface::class)
                );
            }
        );

        $container->share(
            BlogPostModel::class,
            static function (Container $container): BlogPostModel {
                return new BlogPostModel($container->get(SerializerInterface::class));
            }
        );
    }
}
