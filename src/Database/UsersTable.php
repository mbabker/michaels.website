<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Database;

use Joomla\Database\DatabaseDriver;

/**
 * Table class for interfacing with the #__users table
 *
 * @property   integer  $id        Primary key
 * @property   string   $name      The user's name
 * @property   string   $username  The user's username
 * @property   string   $password  The user's password
 * @property   string   $email     The user's e-mail
 * @property   string   $params    Parameters
 *
 * @since      1.0
 */
class UsersTable extends AbstractTable
{
	/**
	 * Constructor.
	 *
	 * @param   DatabaseDriver  $database  A database connector object.
	 *
	 * @since   1.0
	 */
	public function __construct(DatabaseDriver $database)
	{
		parent::__construct('#__users', 'id', $database);
	}

	/**
	 * Fetches the list of users and their password hashes
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getUserPasswords()
	{
		$data = $this->db->setQuery(
			$this->db->getQuery(true)
				->select('username, password')
				->from($this->getTableName())
		)->loadAssocList();

		$users = array();

		foreach ($data as $row)
		{
			$users[$row['username']] = $row['password'];
		}

		return $users;
	}

	/**
	 * Load a user by username
	 *
	 * @param   string  $username  The username of the user to load
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function loadByUserName($userName)
	{
		$check = $this->db->setQuery(
			$this->db->getQuery(true)
				->select('*')
				->from($this->getTableName())
				->where('username = ' . $this->db->quote($userName))
		)->loadObject();

		return ($check) ? $this->bind($check) : $this;
	}
}
