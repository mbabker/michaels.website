<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Doctrine;

use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\ORM\ORMException;

use Joomla\DI\Container;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;

/**
 * Manager Registry for managing Doctrine connections
 *
 * @since  1.0
 */
class ManagerRegistry extends AbstractManagerRegistry implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * Constructor
	 *
	 * @param   Container  $container
	 *
	 * @since   1.0
	 */
	public function __construct(Container $container)
	{
		$this->setContainer($container);

		$connections = [
			'default' => 'doctrine.default.dbal_connection'
		];

		$entityManagers = [
			'default' => 'doctrine.default.entity_manager'
		];

		parent::__construct('ORM', $connections, $entityManagers, 'default', 'default', 'Doctrine\ORM\Proxy\Proxy');
	}

	/**
	 * Resolves a registered namespace alias to the full namespace.
	 *
	 * This method looks for the alias in all registered entity managers.
	 *
	 * @param   string  $alias  The alias
	 *
	 * @return  string  The full namespace
	 *
	 * @see     \Doctrine\ORM\Configuration::getEntityNamespace
	 * @since   1.0
	 * @throws  ORMException
	 */
	public function getAliasNamespace($alias)
	{
		foreach (array_keys($this->getManagers()) as $name)
		{
			try
			{
				return $this->getManager($name)->getConfiguration()->getEntityNamespace($alias);
			}
			catch (ORMException $e)
			{
			}
		}

		throw ORMException::unknownEntityNamespace($alias);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getService($name)
	{
		return $this->container->get($name);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function resetService($name)
	{
		$this->container->set($name, null);
	}
}
