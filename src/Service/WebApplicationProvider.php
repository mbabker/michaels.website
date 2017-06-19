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
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\Renderer\RendererInterface;
use Joomla\Router\Router;

/**
 * Application service provider.
 */
class WebApplicationProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $container->alias(Application::class, JoomlaApplication\AbstractApplication::class)
            ->alias(JoomlaApplication\AbstractWebApplication::class, JoomlaApplication\AbstractApplication::class)
            ->share(
                JoomlaApplication\AbstractApplication::class,
                function (Container $container): JoomlaApplication\AbstractApplication {
                    /** @var Registry $config */
                    $config = $container->get('config');

                    $application = new Application($container->get(Input::class), $config);

                    // Inject extra services
                    $application->setContainer($container);
                    $application->setRouter($container->get(Router::class));

                    if ($config->get('debug', false) && $container->has('debug.bar')) {
                        $application->setDebugBar($container->get('debug.bar'));
                    }

                    return $application;
                },
                true
            );

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
                $controller = new BlogController(
                    $container->get(RendererInterface::class),
                    $container->get(BlogPostModel::class)
                );

                $controller->setApplication($container->get(Application::class));
                $controller->setInput($container->get(Input::class));

                return $controller;
            },
            true
        );

        $container->share(
            BlogPostController::class,
            function (Container $container): BlogPostController {
                $controller = new BlogPostController(
                    $container->get(RendererInterface::class),
                    $container->get(BlogPostModel::class)
                );

                $controller->setApplication($container->get(Application::class));
                $controller->setInput($container->get(Input::class));

                return $controller;
            },
            true
        );

        $container->share(
            HomepageController::class,
            function (Container $container): HomepageController {
                $controller = new HomepageController(
                    $container->get(RendererInterface::class),
                    $container->get(BlogPostModel::class)
                );

                $controller->setApplication($container->get(Application::class));
                $controller->setInput($container->get(Input::class));

                return $controller;
            },
            true
        );

        $container->share(
            PageController::class,
            function (Container $container): PageController {
                $controller = new PageController(
                    $container->get(RendererInterface::class)
                );

                $controller->setApplication($container->get(Application::class));
                $controller->setInput($container->get(Input::class));

                return $controller;
            },
            true
        );

        $container->share(
            BlogPostModel::class,
            function (Container $container): BlogPostModel {
                return new BlogPostModel($container->get('serializer'));
            }
        );
    }
}
