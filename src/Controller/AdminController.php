<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Controller;

use Joomla\Application\AbstractApplication;
use Joomla\Controller\AbstractController;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Input\Input;
use Joomla\Registry\Registry;

/**
 * Base administrator controller class for the application
 *
 * @since  1.0
 */
class AdminController extends DefaultController
{
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

			$template = ucfirst($this->getInput()->getWord('view', $this->defaultView));

			if (is_dir(JPATH_TEMPLATES . '/admin/' . $template))
			{
				$view->getRenderer()->getLoader()->prependPath(JPATH_TEMPLATES . '/admin/' . $template);
			}
		}

		return $view;
	}
}
