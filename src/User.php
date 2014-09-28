<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website;

use BabDev\Website\Database\UsersTable;

use Joomla\Database\DatabaseDriver;
use Joomla\Registry\Registry;

/**
 * Application user object
 *
 * @since  1.0
 */
class User implements \Serializable
{
	/**
	 * User ID
	 *
	 * @var    integer
	 * @since  1.0
	 */
	public $id = 0;

	/**
	 * Username
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $username = '';

	/**
	 * User's hashed password
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $password = '';

	/**
	 * User's name
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $name = '';

	/**
	 * User's e-mail address
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $email = '';

	/**
	 * User parameters.
	 *
	 * @var    Registry
	 * @since  1.0
	 */
	public $params = null;

	/**
	 * Database object
	 *
	 * @var    DatabaseDriver
	 * @since  1.0
	 */
	private $database = null;

	/**
	 * Constructor.
	 *
	 * @param   DatabaseDriver  $database    The database connector.
	 * @param   integer         $identifier  The primary key of the user to load..
	 *
	 * @since   1.0
	 */
	public function __construct(DatabaseDriver $database, $identifier = 0)
	{
		$this->setDatabase($database);

		// Create the user parameters object.
		$this->params = new Registry;

		// Load the user if it exists
		if ($identifier)
		{
			$this->load($identifier);
		}
	}

	/**
	 * Check if the user is authenticated
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function isAuthenticated()
	{
		return $this->id > 0;
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
	public function loadByUsername($username)
	{
		$table = new UsersTable($this->database);
		$table->loadByUsername($username);

		$this->mergeData($table);

		return $this;
	}

	/**
	 * Load a user by ID
	 *
	 * @param   mixed  $identifier  The user id of the user to load.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function load($identifier)
	{
		// Create the user table object
		$table = new UsersTable($this->database);

		// Load the User object based on the user id or throw a warning.
		if (!$table->load($identifier))
		{
			throw new \RuntimeException('Unable to load the user with ID: ' . $identifier);
		}

		$this->mergeData($table);

		return $this;
	}

	/**
	 * Merges the data from the table object into this object
	 *
	 * @param   UsersTable  $table  Table object to merge from
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function mergeData(UsersTable $table)
	{
		// Assuming all is well at this point let's bind the data
		foreach ($table->getFields() as $key => $value)
		{
			if (isset($this->$key) && $key != 'params')
			{
				$this->$key = $table->$key;
			}
		}

		$this->params->loadString($table->params);
	}

	/**
	 * Serialize the object
	 *
	 * @return  string  The string representation of the object or null
	 *
	 * @since   1.0
	 */
	public function serialize()
	{
		$props = array();

		foreach (get_object_vars($this) as $key => $value)
		{
			if (in_array($key, array('database')))
			{
				continue;
			}

			$props[$key] = $value;
		}

		return serialize($props);
	}

	/**
	 * Unserialize the object
	 *
	 * @param   string  $serialized  The serialized string
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function unserialize($serialized)
	{
		$data = unserialize($serialized);

		foreach ($data as $key => $value)
		{
			$this->$key = $value;
		}
	}

	/**
	 * Method to set the database connector.
	 *
	 * @param   DatabaseDriver  $database  The Database connector.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setDatabase(DatabaseDriver $database)
	{
		$this->database = $database;

		return $this;
	}
}
