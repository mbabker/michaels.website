<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Controller;

/**
 * Login controller
 *
 * @since   1.0
 */
class LoginController extends AdminController
{
	/**
	 * Execute the controller
	 *
	 * @return  boolean  True if controller finished execution
	 *
	 * @since   1.0
	 */
	public function execute()
	{
		// If logged in, move on to the manager
		if ($this->getApplication()->getUser()->isAuthenticated())
		{
			$this->getApplication()->redirect($this->getApplication()->get('uri.host') . '/manager');
		}

		$method = $this->getInput()->getMethod();

		$username = $this->getInput()->$method->get('username', false, 'username');
		$password = $this->getInput()->$method->get('password', false, 'raw');

		if ($username && $password)
		{
			$this->getApplication()->login();
		}

		return parent::execute();
	}
}
