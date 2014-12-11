<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Service;

use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * DBAL Connection service provider
 *
 * @since  1.0
 */
class DBALConnectionProvider implements ServiceProviderInterface
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
		$container->set('Doctrine\\DBAL\\Connection',
			function (Container $container)
			{
				$config = $container->get('config');

				// Set the DBAL Connection configuration
				$connection = [
					'driver'   => $config->get('database.driver'),
					'host'     => $config->get('database.host'),
					'user'     => $config->get('database.user'),
					'password' => $config->get('database.password'),
					'dbname'   => $config->get('database.name')
				];

				// Create the Connection
				return DriverManager::getConnection($connection);
			}, true, true
		);

		// Alias the Connection
		$container->set('doctrine.default.dbal_connection', $container->get('Doctrine\\DBAL\\Connection'));
	}
}
