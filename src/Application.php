<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website;

use BabDev\Website\Authentication;
use BabDev\Website\Authentication\DatabaseStrategy;
use BabDev\Website\Controller\DefaultController;
use BabDev\Website\Model\DefaultModel;
use BabDev\Website\View\DefaultHtmlView;

use Joomla\Application\AbstractWebApplication;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Router\Router;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Web application class
 *
 * @since  1.0
 */
final class Application extends AbstractWebApplication implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * The session object.
	 *
	 * @var    Session
	 * @since  1.0
	 * @note   This has been created to avoid a conflict with the $session member var from the parent class.
	 */
	private $newSession = null;

	/**
	 * The User object.
	 *
	 * @var    User
	 * @since  1.0
	 */
	private $user;

	/**
	 * Clear the system message queue.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function clearMessageQueue()
	{
		$this->getSession()->getFlashBag()->clear();
	}

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
				->setDefaultController('\\Controller\\DefaultController')
				->addMap('/manage', '\\Controller\\LoginController')
				->addMap('/:view', '\\Controller\\DefaultController')
				->addMap('/blog/:alias', '\\Controller\\BlogController');

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
	 * Enqueue a system message.
	 *
	 * @param   string  $msg   The message to enqueue.
	 * @param   string  $type  The message type. Default is message.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function enqueueMessage($msg, $type = 'message')
	{
		$this->getSession()->getFlashBag()->add($type, $msg);

		return $this;
	}

	/**
	 * Get the system message queue.
	 *
	 * @return  array  The system message queue.
	 *
	 * @since   1.0
	 */
	public function getMessageQueue()
	{
		return $this->getSession()->getFlashBag()->peekAll();
	}

	/**
	 * Get a session object.
	 *
	 * @return  Session
	 *
	 * @since   1.0
	 */
	public function getSession()
	{
		if (is_null($this->newSession))
		{
			$this->newSession = new Session;

			$this->newSession->start();

			$registry = $this->newSession->get('registry');

			if (is_null($registry))
			{
				$this->newSession->set('registry', new Registry('session'));
			}
		}

		return $this->newSession;
	}

	/**
	 * Get a user object.
	 *
	 * @param   integer  $id  The user id or the current user.
	 *
	 * @return  User
	 *
	 * @since   1.0
	 */
	public function getUser($id = 0)
	{
		if ($id)
		{
			return new User($this->getContainer()->get('db'), $id);
		}

		if (is_null($this->user))
		{
			if ($this->user = $this->getSession()->get('babdev_user'))
			{
				$this->user->setDatabase($this->getContainer()->get('db'));
			}
			else
			{
				$this->user = new User($this->getContainer()->get('db'));
			}
		}

		return $this->user;
	}

	/**
	 * Gets a user state.
	 *
	 * @param   string  $key      The path of the state.
	 * @param   mixed   $default  Optional default value, returned if the internal value is null.
	 *
	 * @return  mixed  The user state or null.
	 *
	 * @since   1.0
	 */
	public function getUserState($key, $default = null)
	{
		/* @type Registry $registry */
		$registry = $this->getSession()->get('registry');

		if (!is_null($registry))
		{
			return $registry->get($key, $default);
		}

		return $default;
	}

	/**
	 * Gets the value of a user state variable.
	 *
	 * @param   string  $key      The key of the user state variable.
	 * @param   string  $request  The name of the variable passed in a request.
	 * @param   mixed   $default  The default value for the variable if not found. Optional.
	 * @param   string  $type     Filter for the variable, for valid values see \Joomla\Filter\InputFilter::clean(). Optional.
	 *
	 * @return  mixed  The request user state.
	 *
	 * @see     \Joomla\Filter\InputFilter::clean()
	 * @since   1.0
	 */
	public function getUserStateFromRequest($key, $request, $default = null, $type = 'none')
	{
		$cur_state = $this->getUserState($key, $default);
		$new_state = $this->input->get($request, null, $type);

		// Save the new value only if it was set in this request.
		if ($new_state !== null)
		{
			$this->setUserState($key, $new_state);
		}
		else
		{
			$new_state = $cur_state;
		}

		return $new_state;
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
	 * Logs the user into the application
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function login()
	{
		// Get the Authentication object
		$authentication = new Authentication;

		// Add our authentication strategy
		$authentication->addStrategy('database', new DatabaseStrategy($this->input));

		// Authenticate the user
		$authentication->authenticate(array('database'));

		$results = $authentication->getResults();
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

	/**
	 * Set the system message queue for a given type.
	 *
	 * @param   string  $type     The type of message to set
	 * @param   mixed   $message  Either a single message or an array of messages
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setMessageQueue($type, $message = '')
	{
		$this->getSession()->getFlashBag()->set($type, $message);
	}

	/**
	 * Login or logout a user.
	 *
	 * @param   User|null  $user  The User object or null to set a guest user.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setUser(User $user = null)
	{
		$this->user = is_null($user) ? new User : $user;
		$this->getSession()->set('babdev_user', $this->user);

		return $this;
	}

	/**
	 * Sets the value of a user state variable.
	 *
	 * @param   string  $key    The path of the state.
	 * @param   string  $value  The value of the variable.
	 *
	 * @return  mixed  The previous state, if one existed.
	 *
	 * @since   1.0
	 */
	public function setUserState($key, $value)
	{
		/* @type Registry $registry */
		$registry = $this->getSession()->get('registry');

		if (!is_null($registry))
		{
			return $registry->set($key, $value);
		}

		return null;
	}
}
