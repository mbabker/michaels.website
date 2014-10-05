<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Cli\Command;

use BabDev\Website\Cli\Application;
use BabDev\Website\Cli\Exception\AbortException;
use BabDev\Website\Database\UsersTable;

/**
 * Class to install the application.
 *
 * @since  1.0
 */
class Install
{
	/**
	 * Application object
	 *
	 * @var    Application
	 * @since  1.0
	 */
	private $app;

	/**
	 * Configuration data
	 *
	 * @var    \Joomla\Registry\Registry
	 * @since  1.0
	 */
	private $config;

	/**
	 * Database driver object
	 *
	 * @var    \Joomla\Database\DatabaseDriver
	 * @since  1.0
	 */
	private $db;

	/**
	 * Class constructor
	 *
	 * @param   Application  $app  Application object
	 *
	 * @since   1.0
	 */
	public function __construct(Application $app)
	{
		$this->app    = $app;
		$this->config = $this->app->getContainer()->get('config');
		$this->db     = $this->app->getContainer()->get('db');
	}

	/**
	 * Execute the command.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  AbortException
	 * @throws  \RuntimeException
	 * @throws  \UnexpectedValueException
	 */
	public function execute()
	{
		try
		{
			// Check if the database "exists"
			$tables = $this->db->getTableList();

			if (!$this->app->input->get('reinstall'))
			{
				$this->app->out('<fg=black;bg=yellow>WARNING: A database has been found!!</fg=black;bg=yellow>')
					->out('Do you want to reinstall ? [y]es / [[n]]o :', false);

				if (!in_array(trim($this->app->in()), ['yes', 'y']))
				{
					throw new AbortException('Aborting installation, database already exists and elected to not dump it.');
				}
			}

			$this->cleanDatabase($tables);

			$this->app->out("\nFinished!");
		}
		catch (\RuntimeException $e)
		{
			// Check if the message is "Could not connect to database."  Odds are, this means the DB isn't there or the server is down.
			if (strpos($e->getMessage(), 'Could not connect to database.') === false)
			{
				throw $e;
			}

			$this->app->out('Could not connect to the database, attempting to create a new database.', false);

			$query = 'CREATE DATABASE ' . $this->db->quoteName($this->config->get('database.name'));

			if ($this->db->hasUTFSupport())
			{
				$query .= ' CHARACTER SET ' . $this->db->quoteName('utf8');
			}

			$this->db->setQuery($query)->execute();

			$this->db->select($this->config->get('database.name'));

			$this->app->out("\nFinished!");
		}

		// Perform the installation
		$this->processSql();

		// Create the admin user
		$this->createAdmin();

		$this->app->out('Installer has terminated successfully.');
	}

	/**
	 * Cleanup the database.
	 *
	 * @param   array  $tables  Tables to remove.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	private function cleanDatabase(array $tables)
	{
		$this->app->out('Removing existing tables...', false);

		// Foreign key constraint fails fix
		if (in_array($this->db->name, ['mysqli', 'mysql']))
		{
			$this->db->setQuery('SET FOREIGN_KEY_CHECKS=0')->execute();
		}

		foreach ($tables as $table)
		{
			if ($table == 'sqlite_sequence')
			{
				continue;
			}

			$this->db->dropTable($table);
			$this->app->out('.', false);
		}

		if (in_array($this->db->name, ['mysqli', 'mysql']))
		{
			$this->db->setQuery('SET FOREIGN_KEY_CHECKS=1')->execute();
		}

		return $this;
	}

	/**
	 * Create the administrator user
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  AbortException
	 */
	private function createAdmin()
	{
		$data = new \stdClass;
		$data->name     = 'Administrator';
		$data->username = 'admin';
		$data->password = password_hash('admin', PASSWORD_BCRYPT);
		$data->email    = 'admin@michaels.website';

		try
		{
			$user = new UsersTable($this->db);
			$user->save($data);
		}
		catch (\Exception $exception)
		{
			throw new AbortException('An error occurred creating the admin user: ' . $exception->getMessage());
		}

		$this->app->out('An administrative user has been created with admin/admin as the credentials.');
	}

	/**
	 * Process the main SQL file.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 * @throws  \UnexpectedValueException
	 */
	private function processSql()
	{
		$dbType = $this->db->name;

		if ($dbType == 'mysqli')
		{
			$dbType = 'mysql';
		}

		$fName = JPATH_ROOT . '/etc/schemas/' . $dbType . '.sql';

		if (!file_exists($fName))
		{
			throw new \UnexpectedValueException('Install SQL file not found.');
		}

		$sql = file_get_contents($fName);

		if (!$sql)
		{
			throw new \UnexpectedValueException('Unable to read SQL file.');
		}

		$this->app->out(sprintf('Creating tables from file %s', realpath($fName)), false);

		foreach ($this->db->splitSql($sql) as $query)
		{
			$q = trim($this->db->replacePrefix($query));

			if (trim($q) == '')
			{
				continue;
			}

			$this->db->setQuery($q)->execute();

			$this->app->out('.', false);
		}

		$this->app->out("\nFinished!");

		return $this;
	}
}
