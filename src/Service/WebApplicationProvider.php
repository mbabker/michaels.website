<?php declare(strict_types=1);

namespace BabDev\Website\Service;

use BabDev\Website\Controller\BlogController;
use BabDev\Website\Controller\BlogPostController;
use BabDev\Website\Controller\HomepageController;
use BabDev\Website\Controller\PageController;
use BabDev\Website\Model\BlogPostModel;
use Joomla\Application\AbstractApplication;
use Joomla\Application\AbstractWebApplication;
use Joomla\Application\Controller\ContainerControllerResolver;
use Joomla\Application\Controller\ControllerResolverInterface;
use Joomla\Application\WebApplication;
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
        $container->share(
            WebApplication::class,
            function (Container $container): WebApplication {
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
            },
            true
        )
            ->alias(AbstractWebApplication::class, WebApplication::class)
            ->alias(AbstractApplication::class, WebApplication::class);

        $container->share(
            ControllerResolverInterface::class,
            function (Container $container): ControllerResolverInterface {
                return new ContainerControllerResolver($container);
            }
        )
            ->alias(ContainerControllerResolver::class, ControllerResolverInterface::class);

        $container->share(
            Input::class,
            function (): Input {
                return new Input($_REQUEST);
            },
            true
        );

        $container->share(
            RouterInterface::class,
            function (Container $container): RouterInterface {
                return (new Router())
                    ->get('/', HomepageController::class)
                    ->get('/blog', BlogController::class)
                    ->get('/blog/page/:page', BlogController::class, ['page' => '(\d+)'])
                    ->get('/blog/:alias', BlogPostController::class)
                    ->get('/:view', PageController::class);
            },
            true
        )
            ->alias(Router::class, RouterInterface::class);

        $container->share(
            BlogController::class,
            function (Container $container): BlogController {
                return new BlogController(
                    $container->get(RendererInterface::class),
                    $container->get(BlogPostModel::class),
                    $container->get(AbstractApplication::class),
                    $container->get(Input::class)
                );
            },
            true
        );

        $container->share(
            BlogPostController::class,
            function (Container $container): BlogPostController {
                return new BlogPostController(
                    $container->get(RendererInterface::class),
                    $container->get(BlogPostModel::class),
                    $container->get(AbstractApplication::class),
                    $container->get(Input::class)
                );
            },
            true
        );

        $container->share(
            HomepageController::class,
            function (Container $container): HomepageController {
                return new HomepageController(
                    $container->get(RendererInterface::class),
                    $container->get(BlogPostModel::class),
                    $container->get(AbstractApplication::class),
                    $container->get(Input::class)
                );
            },
            true
        );

        $container->share(
            PageController::class,
            function (Container $container): PageController {
                return new PageController(
                    $container->get(RendererInterface::class),
                    $container->get(AbstractApplication::class),
                    $container->get(Input::class)
                );
            },
            true
        );

        $container->share(
            BlogPostModel::class,
            function (Container $container): BlogPostModel {
                return new BlogPostModel($container->get(SerializerInterface::class));
            }
        );
    }
}
