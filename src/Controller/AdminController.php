<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Controller;

use Joomla\View\BaseHtmlView;

/**
 * Base administrator controller class for the application
 *
 * @since  1.0
 */
class AdminController extends DefaultController
{
	/**
	 * The default view for the application
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $defaultView = 'dashboard';

	/**
	 * Method to initialize the controller object, called after the parent constructor has been processed
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function initializeController()
	{
		if (!$this->initialized)
		{
			// Redirect unauthenticated users out
			if (!$this->getApplication()->getUser()->isAuthenticated() && $this->getApplication()->get('uri.route') != 'manage')
			{
				$this->getApplication()->enqueueMessage('Must login first!');
				$this->getApplication()->redirect($this->getApplication()->get('uri.host') . '/manage');
			}

			parent::initializeController();
		}
	}

	/**
	 * Method to initialize the view object
	 *
	 * @return  \Joomla\View\ViewInterface  View object
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function initializeView()
	{
		$view = parent::initializeView();

		// Add the admin template path to the lookup if it exists for HTML views
		if ($view instanceof BaseHtmlView)
		{
			if (is_dir(JPATH_TEMPLATES . '/admin'))
			{
				$view->getRenderer()->getRenderer()->getLoader()->prependPath(JPATH_TEMPLATES . '/admin');
			}

			// Add the extension's view path if it exists
			if (is_dir(JPATH_TEMPLATES . '/admin/' . strtolower($this->extension)))
			{
				$view->getRenderer()->getRenderer()->getLoader()->prependPath(JPATH_TEMPLATES . '/admin/' . strtolower($this->extension));
			}
		}

		return $view;
	}
}
