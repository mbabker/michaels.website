<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Cli\Command;

use BabDev\Website\Cli\Application;

/**
 * CLI command for synchronizing a server with the primary git repository
 *
 * @since  1.0
 */
class UpdateServer
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
	 * @throws  \DomainException
	 * @throws  \RuntimeException
	 */
	public function execute()
	{
		$this->app->out('Updating server to git HEAD');

		// Pull from remote repo
		$this->app->runCommand('cd ' . JPATH_ROOT . ' && git pull 2>&1');

		$this->app->out('Updating Composer resources');

		// Run Composer update
		$this->app->runCommand('cd ' . JPATH_ROOT . ' && composer install --no-dev 2>&1');

		$this->app->out('Update Finished');
	}
}
