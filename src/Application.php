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

/**
 * Web application class
 *
 * @since  1.0
 */
final class Application extends AbstractWebApplication implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * Application router.
	 *
	 * @var    Router
	 * @since  1.0
	 */
	private $router;

	/**
	 * The User object.
	 *
	 * @var    User
	 * @since  1.0
	 */
	private $user;

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
			// Fetch and execute the controller
			$this->router->getController($this->get('uri.route'))->execute();
		}
		catch (\Throwable $throwable)
		{
			$this->setErrorHeader($throwable);
			$this->setErrorOutput($throwable);
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
	 * @param   \Throwable  $throwable  The Throwable object
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	private function setErrorHeader(\Throwable $exception)
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
	 * @param   \Throwable  $throwable  The Throwable object
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	private function setErrorOutput(\Throwable $exception)
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
				$body = $this->getContainer()->get('renderer')->render('exception.html.twig', ['exception' => $exception]);

				break;
		}

		$this->setBody($body);
	}

	/**
	 * Set the application's router.
	 *
	 * @param   Router  $router  Router object to set.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setRouter(Router $router)
	{
		$this->router = $router;

		return $this;
	}
}
