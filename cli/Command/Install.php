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
use BabDev\Website\Entity\Users;

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
	 * Class constructor
	 *
	 * @param   Application  $app  Application object
	 *
	 * @since   1.0
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
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
		$this->app->runCommand('php ' . JPATH_ROOT . '/vendor/bin/doctrine orm:schema-tool:drop --force');
		$this->app->runCommand('php ' . JPATH_ROOT . '/vendor/bin/doctrine orm:schema-tool:create');

		// Create the admin user
		$this->createAdmin();

		$this->app->out('Installer has completed successfully.');
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
		$user = (new Users)
			->setName('Administrator')
			->setUsername('admin')
			->setPassword(password_hash('admin', PASSWORD_BCRYPT))
			->setEmail('admin@michaels.website');

		try
		{
			/** @var \Doctrine\ORM\EntityManager $em */
			$em = $this->app->getContainer()->get('em');
			$em->persist($user);
			$em->flush();
		}
		catch (\Exception $exception)
		{
			throw new AbortException('An error occurred creating the admin user: ' . $exception->getMessage());
		}

		$this->app->out('An administrative user has been created with admin/admin as the credentials.');
	}
}
