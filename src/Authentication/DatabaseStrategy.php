<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Authentication;

use BabDev\Website\Database\UsersTable;

use Joomla\Authentication\AuthenticationStrategyInterface;
use Joomla\Authentication\Authentication;
use Joomla\Database\DatabaseDriver;
use Joomla\Input\Input;

class DatabaseStrategy implements AuthenticationStrategyInterface
{
	/**
	 * The Input object
	 *
	 * @var    Input  $input  The input object from which to retrieve the username and password.
	 * @since  1.0
	 */
	private $input;

	/**
	 * The credential store.
	 *
	 * @var    array  $credentialStore  An array of username/hash pairs.
	 * @since  1.0
	 */
	private $credentialStore;

	/**
	 * The last authentication status.
	 *
	 * @var    integer  $status  The last status result (use constants from Authentication)
	 * @since  1.0
	 */
	private $status;

	/**
	 * Strategy Constructor
	 *
	 * @param   Input           $input     The input object from which to retrieve the request credentials.
	 * @param   DatabaseDriver  $database  Database object
	 *
	 * @since   1.0
	 */
	public function __construct(Input $input, DatabaseDriver $database)
	{
		$this->input = $input;

		$usersTable = new UsersTable($database);
		$this->credentialStore = $usersTable->getUserPasswords();
	}

	/**
	 * Attempt to authenticate the username and password pair.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function authenticate()
	{
		$method = $this->input->getMethod();

		$username = $this->input->$method->get('username', false, 'username');
		$password = $this->input->$method->get('password', false, 'raw');

		if (!$username || !$password)
		{
			$this->status = Authentication::NO_CREDENTIALS;

			return false;
		}

		if (isset($this->credentialStore[$username]))
		{
			$hash = $this->credentialStore[$username];
		}
		else
		{
			$this->status = Authentication::NO_SUCH_USER;

			return false;
		}

		if (\password_verify($password, $hash))
		{
			$this->status = Authentication::SUCCESS;

			return $username;
		}
		else
		{
			$this->status = Authentication::INVALID_CREDENTIALS;

			return false;
		}
	}

	/**
	 * Get the status of the last authentication attempt.
	 *
	 * @return  integer  Authentication class constant result.
	 *
	 * @since   1.0
	 */
	public function getResult()
	{
		return $this->status;
	}
}
