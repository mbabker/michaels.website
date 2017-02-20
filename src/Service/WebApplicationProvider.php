<?php

namespace BabDev\Website\Service;

use BabDev\Website\Application;
use BabDev\Website\Controller\HomepageController;
use BabDev\Website\Controller\RenderController;
use BabDev\Website\Model\BlogPostModel;
use BabDev\Website\Router;
use Joomla\Application as JoomlaApplication;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Input\Input;
use Joomla\Renderer\RendererInterface;

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
                    $application = new Application($container->get(Input::class), $container->get('config'));

                    // Inject extra services
                    $application->setContainer($container);
                    $application->setRouter($container->get(Router::class));

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
                $router = (new Router($container->get(Input::class)))
                    ->setControllerPrefix('BabDev\\Website\\Controller\\')
                    ->setDefaultController('HomepageController')
                    ->addMap('/:slug', 'RenderController')
                    ->addMap('/:slug/*', 'RenderController')
                    ->setContainer($container);

                return $router;
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
            RenderController::class,
            function (Container $container): RenderController {
                $controller = new RenderController(
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
                return new BlogPostModel();
            }
        );
    }
}
