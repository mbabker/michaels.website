<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Controller;

/**
 * Base administrator controller class for the application
 *
 * @since  1.0
 */
class AdminController extends DefaultController
{
	/**
	 * Method to initialize the controller object, called after the parent constructor has been processed
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function initializeController()
	{
		$replacements = [__NAMESPACE__ . '\\', 'Extensions\\' . $this->extension . '\\Controller\\', 'Controller'];
		$defaultView = strtolower(str_replace($replacements, '', get_called_class()));
		$this->defaultView = ($defaultView == 'admin') ? 'dashboard' : $defaultView;
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
		if (strtolower($this->getInput()->getWord('format', 'html')) == 'html')
		{
			if (is_dir(JPATH_TEMPLATES . '/admin'))
			{
				$view->getRenderer()->getLoader()->prependPath(JPATH_TEMPLATES . '/admin');
			}

			// Add the extension's view path if it exists
			if (is_dir(JPATH_TEMPLATES . '/admin/' . strtolower($this->extension)))
			{
				$view->getRenderer()->getLoader()->prependPath(JPATH_TEMPLATES . '/admin/' . strtolower($this->extension));
			}
		}

		return $view;
	}
}
