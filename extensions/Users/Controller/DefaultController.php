<?php
/**
 * Users extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Users\Controller;

use BabDev\Website\Controller\AdminController;

/**
 * Default controller for the users extension
 *
 * @since  1.0
 */
class DefaultController extends AdminController
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
		$this->defaultView = 'lists';
	}
}
