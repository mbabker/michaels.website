<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Service;

use Joomla\Application as JoomlaApplication;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Input\Input;
use Joomla\Renderer\RendererInterface;
use BabDev\Website\Application;
use BabDev\Website\Controller\RenderController;
use BabDev\Website\Router;

/**
 * Application service provider
 *
 * @since  1.0
 */
class WebApplicationProvider implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function register(Container $container)
	{
		$container->alias(Application::class, JoomlaApplication\AbstractApplication::class)
			->alias(JoomlaApplication\AbstractWebApplication::class, JoomlaApplication\AbstractApplication::class)
			->share(
				JoomlaApplication\AbstractApplication::class,
				function (Container $container)
				{
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
			function ()
			{
				return new Input($_REQUEST);
			},
			true
		);

		$container->share(
			Router::class,
			function (Container $container)
			{
				$router = (new Router($container->get(Input::class)))
					->setControllerPrefix('BabDev\\Website\\Controller\\')
					->setDefaultController('RenderController')
					->addMap('/:slug', 'RenderController')
					->addMap('/:slug/*', 'RenderController')
					->setContainer($container);

				return $router;
			},
			true
		);

		$container->share(
			RenderController::class,
			function (Container $container)
			{
				$controller = new RenderController(
					$container->get(RendererInterface::class)
				);

				$controller->setApplication($container->get(Application::class));
				$controller->setInput($container->get(Input::class));

				return $controller;
			},
			true
		);
	}
}
