<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Service;

use BabDev\Website\Doctrine\ManagerRegistry;

use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * Doctrine service provider
 *
 * @since  1.0
 */
class DoctrineProvider implements ServiceProviderInterface
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
		// Register the DBAL Connection
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

		// Register the Entity Manager
		$container->set('Doctrine\\ORM\\EntityManager',
			function (Container $container)
			{
				$config = $container->get('config');

				// Lookup path for entities
				$paths = [JPATH_ROOT . '/src/Entity'];

				// Flag if we're in dev mode, base this on the database.debug option
				$devMode = $config->get('database.debug');

				// Setup the configuration
				$emConfig = Setup::createAnnotationMetadataConfiguration($paths, $devMode);
				$emConfig->setAutoGenerateProxyClasses(AbstractProxyFactory::AUTOGENERATE_FILE_NOT_EXISTS);

				// Create the EntityManager
				return EntityManager::create($container->get('doctrine.default.dbal_connection'), $emConfig);
			}, true, true
		);

		// Alias the EntityManager
		$container->alias('doctrine.default.entity_manager', 'Doctrine\\ORM\\EntityManager');

		// Register the Doctrine Manager Registry
		$container->set('BabDev\\Website\\Doctrine\\ManagerRegistry',
			function (Container $container)
			{
				return new ManagerRegistry($container);
			}, true, true
		);

		// Alias the ManagerRegistry
		$container->alias('doctrine', 'BabDev\\Website\\Doctrine\\ManagerRegistry');
	}
}
