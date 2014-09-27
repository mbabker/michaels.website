<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website;

use Joomla\Application\AbstractWebApplication;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Router\Router;

use BabDev\Website\Controller\DefaultController;
use BabDev\Website\Model\DefaultModel;
use BabDev\Website\View\DefaultHtmlView;

/**
 * Web application class
 *
 * @since  1.0
 */
final class Application extends AbstractWebApplication implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * Method to run the application routines
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function doExecute()
	{
		try
		{
			// Instantiate the router
			$router = (new Router($this->input))
				->setControllerPrefix('\\BabDev\\Website')
				->setDefaultController('\\Controller\\DefaultController');

			// Fetch the controller
			/* @type  \Joomla\Controller\AbstractController  $controller */
			$controller = $router->getController($this->get('uri.route'));

			// Inject the DI container and application into the controller and execute it
			$controller->setContainer($this->getContainer())->setApplication($this)->execute();
		}
		catch (\Exception $exception)
		{
			$this->setErrorHeader($exception);
			$this->setErrorOutput($exception);
		}
	}

	/**
	 * Custom initialisation method
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function initialise()
	{
		// Set the MIME for the application based on format
		switch (strtolower($this->input->getWord('format', 'html')))
		{
			case 'json' :
				$this->mimeType = 'application/json';

				break;

			// Don't need to do anything for the default case
			default :
				break;
		}
	}

	/**
	 * Set the HTTP Response Header for error conditions
	 *
	 * @param   \Exception  $exception  The Exception object
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	private function setErrorHeader(\Exception $exception)
	{
		switch ($exception->getCode())
		{
			case 404 :
				$this->setHeader('HTTP/1.1 404 Not Found', 404, true);

				break;

			case 500 :
			default  :
				$this->setHeader('HTTP/1.1 500 Internal Server Error', 500, true);

				break;
		}
	}

	/**
	 * Set the body for error conditions
	 *
	 * @param   \Exception  $exception  The Exception object
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	private function setErrorOutput(\Exception $exception)
	{
		switch (strtolower($this->input->getWord('format', 'html')))
		{
			case 'json' :
				$data = [
					'code'    => $exception->getCode(),
					'message' => $exception->getMessage(),
					'error'   => true
				];

				$body = json_encode($data);

				break;

			case 'html' :
			default :
				// Need the default controller in order to fetch the renderer
				$controller = (new DefaultController($this->input, $this))->setContainer($this->getContainer());

				// Build a default view object and render with the exception layout
				$controller->initializeRenderer();
				$view = new DefaultHtmlView(new DefaultModel($this->getContainer()->get('db')), $this->getContainer()->get('renderer'));

				$body = $view->setLayout('exception')->setData(['exception' => $exception])->render();

				break;
		}

		$this->setBody($body);
	}
}
