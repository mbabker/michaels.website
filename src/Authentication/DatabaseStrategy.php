<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Authentication;

use BabDev\Website\Database\UsersTable;

use Doctrine\ORM\EntityManager;

use Joomla\Authentication\AuthenticationStrategyInterface;
use Joomla\Authentication\Authentication;
use Joomla\Input\Input;

/**
 * Authentication strategy which pulls user data from the database
 *
 * @since  1.0
 */
class DatabaseStrategy implements AuthenticationStrategyInterface
{
	/**
	 * The credential store.
	 *
	 * @var    array
	 * @since  1.0
	 */
	private $credentialStore;

	/**
	 * The Input object
	 *
	 * @var    Input
	 * @since  1.0
	 */
	private $input;

	/**
	 * The EntityManager object
	 *
	 * @var    EntityManager
	 * @since  1.0
	 */
	private $em;

	/**
	 * The last authentication status.
	 *
	 * @var    integer
	 * @since  1.0
	 */
	private $status;

	/**
	 * Strategy Constructor
	 *
	 * @param   Input          $input  The input object from which to retrieve the request credentials.
	 * @param   EntityManager  $em     EntityManager object
	 *
	 * @since   1.0
	 * @throws  AuthenticationException
	 */
	public function __construct(Input $input, EntityManager $em)
	{
		$this->input = $input;
		$this->em    = $em;

		$passwords = $this->getUserPasswords();

		if (empty($passwords))
		{
			throw new AuthenticationException('Unable to retrieve user data.');
		}

		$this->credentialStore = $passwords;
	}

	/**
	 * Attempt to authenticate the username and password pair.
	 *
	 * @return  string|boolean  A string containing a username if authentication is successful, false otherwise.
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

		if (!isset($this->credentialStore[$username]))
		{
			$this->status = Authentication::NO_SUCH_USER;

			return false;
		}

		$hash = $this->credentialStore[$username];

		if (!password_verify($password, $hash))
		{
			$this->status = Authentication::INVALID_CREDENTIALS;

			return false;
		}

		$this->status = Authentication::SUCCESS;

		return $username;
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

	/**
	 * Fetch the usernames and passwords from the database
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	private function getUserPasswords()
	{
		$query = $this->em->getConnection()->createQueryBuilder();
		$query->select('u.username, u.password')
			->from('users', 'u');

		$results = $query->execute()->fetchAll();

		$return = [];

		foreach ($results as $result)
		{
			$return[$result['username']] = $result['password'];
		}

		return $return;
	}
}
