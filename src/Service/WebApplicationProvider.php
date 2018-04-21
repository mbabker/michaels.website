<?php

namespace BabDev\Website\Service;

use BabDev\Website\Application;
use BabDev\Website\Controller\BlogController;
use BabDev\Website\Controller\BlogPostController;
use BabDev\Website\Controller\HomepageController;
use BabDev\Website\Controller\PageController;
use BabDev\Website\Model\BlogPostModel;
use Joomla\Application as JoomlaApplication;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\Renderer\RendererInterface;
use Joomla\Router\Router;
use Symfony\Component\Serializer\SerializerInterface;

final class WebApplicationProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->share(
            JoomlaApplication\AbstractApplication::class,
            function (Container $container): JoomlaApplication\AbstractApplication {
                /** @var Registry $config */
                $config = $container->get('config');

                $application = new Application(
                    $container->get(JoomlaApplication\Controller\ControllerResolverInterface::class),
                    $container->get(Router::class),
                    $container->get(Input::class),
                    $config
                );

                // Inject extra services
                $application->setDispatcher($container->get(DispatcherInterface::class));

                return $application;
            },
            true
        )
            ->alias(Application::class, JoomlaApplication\AbstractApplication::class);

        $container->share(
            JoomlaApplication\Controller\ControllerResolverInterface::class,
            function (Container $container): JoomlaApplication\Controller\ControllerResolverInterface {
                return new JoomlaApplication\Controller\ContainerControllerResolver($container);
            }
        )
            ->alias(JoomlaApplication\Controller\ContainerControllerResolver::class, JoomlaApplication\Controller\ControllerResolverInterface::class);

        $container->share(
            Input::class,
            function (): Input {
                return new Input($_REQUEST);
            },
            true
        );

        $container->share(
            Router::class,
            function (Container $container): Router {
                return (new Router())
                    ->get('/', HomepageController::class)
                    ->get('/blog', BlogController::class)
                    ->get('/blog/page/:page', BlogController::class, ['page' => '(\d+)'])
                    ->get('/blog/:alias', BlogPostController::class)
                    ->get('/:view', PageController::class);
            },
            true
        );

        $container->share(
            BlogController::class,
            function (Container $container): BlogController {
                return new BlogController(
                    $container->get(RendererInterface::class),
                    $container->get(BlogPostModel::class),
                    $container->get(Application::class),
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
                    $container->get(Application::class),
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
                    $container->get(Application::class),
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
                    $container->get(Application::class),
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
