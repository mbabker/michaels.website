<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Service;

use Doctrine\Common\Proxy\AbstractProxyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

/**
 * EntityManager service provider
 *
 * @since  1.0
 */
class EntityManagerProvider implements ServiceProviderInterface
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
	}
}
