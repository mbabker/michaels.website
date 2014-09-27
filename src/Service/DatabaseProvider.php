<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Service;

use Joomla\Database\DatabaseDriver;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * Database service provider
 *
 * @since  1.0
 */
class DatabaseProvider implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function register(Container $container)
	{
		$container->set('Joomla\\Database\\DatabaseDriver',
			function (Container $container)
			{
				$config = $container->get('config');

				$options = array(
					'driver'   => $config->get('database.driver'),
					'host'     => $config->get('database.host'),
					'user'     => $config->get('database.user'),
					'password' => $config->get('database.password'),
					'database' => $config->get('database.name'),
					'prefix'   => $config->get('database.prefix')
				);

				$db = DatabaseDriver::getInstance($options);
				$db->setDebug($config->get('database.debug', false));

				return $db;
			}, true, true
		);

		// Alias the database
		$container->alias('db', 'Joomla\\Database\\DatabaseDriver');
	}
}
