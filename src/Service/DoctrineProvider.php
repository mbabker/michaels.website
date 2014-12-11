<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Service;

use BabDev\Website\Doctrine\ManagerRegistry;

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
		// Register dependencies
		$container->registerServiceProvider(new DBALConnectionProvider)
			->registerServiceProvider(new EntityManagerProvider);

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
