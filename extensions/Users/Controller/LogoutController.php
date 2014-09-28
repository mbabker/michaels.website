<?php
/**
 * Users extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Users\Controller;

use BabDev\Website\Controller\DefaultController;

/**
 * Logout controller
 *
 * @since   1.0
 */
class LogoutController extends DefaultController
{
	/**
	 * Execute the controller
	 *
	 * @return  void  Redirects the application
	 *
	 * @since   1.0
	 */
	public function execute()
	{
		$this->getApplication()->setUser(null)->redirect($this->getApplication()->get('uri.host'));
	}
}
