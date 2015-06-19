<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Cli;

use BabDev\Website\Cli\Command\Install;
use BabDev\Website\Cli\Command\UpdateServer;
use BabDev\Website\Service\ConfigurationProvider;
use BabDev\Website\Service\DoctrineProvider;
use BabDev\Website\Service\TwigRendererProvider;

use Joomla\Application\AbstractCliApplication;
use Joomla\DI\Container;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;

/**
 * CLI application supporting the base application
 *
 * @since  1.0
 */
class Application extends AbstractCliApplication implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * Constructor
	 *
	 * @since   1.0
	 */
	public function __construct()
	{
		$container = (new Container)
			->registerServiceProvider(new ConfigurationProvider)
			->registerServiceProvider(new DoctrineProvider)
			->registerServiceProvider(new TwigRendererProvider);

		$this->setContainer($container);

		// Set error reporting based on config
		$errorReporting = (int) $container->get('config')->get('errorReporting', 0);
		error_reporting($errorReporting);

		parent::__construct(null, $container->get('config'));
	}

	/**
	 * Method to run the application routines.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function doExecute()
	{
		// If --install option provided, run the install routine to set up the database
		if ($this->input->getBool('install', false))
		{
			(new Install($this))->execute();
		}
		// If --updateserver option provided, run the update routine
		elseif ($this->input->getBool('updateserver', false))
		{
			(new UpdateServer($this))->execute();
		}

		$this->out('Finished!');
	}

	/**
	 * Execute a command on the server.
	 *
	 * @param   string  $command  The command to execute.
	 *
	 * @return  string  Return data from the command
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function runCommand($command)
	{
		$lastLine = system($command, $status);

		if ($status)
		{
			// Command exited with a status != 0
			if ($lastLine)
			{
				$this->out($lastLine);

				throw new \RuntimeException($lastLine);
			}

			$this->out('An unknown error occurred');

			throw new \RuntimeException('An unknown error occurred');
		}

		return $lastLine;
	}
}
