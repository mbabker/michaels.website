<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website;

use Doctrine\ORM\Mapping\ClassMetadata;

use Joomla\DI\Container;

/**
 * Application Factory
 *
 * @since  1.0
 */
class Factory
{
	/**
	 * DI Container
	 *
	 * @var    Container
	 * @since  1.0
	 */
	private $container;

	/**
	 * Singleton Factory instance
	 *
	 * @var    Factory
	 * @since  1.0
	 */
	private static $instance;

	/**
	 * Constructor
	 *
	 * @param   Container  $container  DI Container
	 *
	 * @since   1.0
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
		self::$instance  = $this;
	}

	/**
	 * Fetch the DI container
	 *
	 * @return  Container
	 *
	 * @since   1.0
	 */
	public function getContainer()
	{
		return $this->container;
	}

	/**
	 * Retrieves the Repository object for the given entity
	 *
	 * @param   string  $entity  The fully qualified class name of the entity
	 *
	 * @return  \BabDev\Website\Entity\BaseRepository
	 *
	 * @since   1.0
	 */
	private static function getRepository($entity)
	{
		$repo = $entity . 'Repository';

		if (!class_exists($repo))
		{
			throw new \InvalidArgumentException('A valid repository class was not found.');
		}

		return new $repo(self::$instance->getContainer()->get('em'), new ClassMetadata($entity));
	}

	/**
	 * Retrieve objects from the DI container
	 *
	 * @param   string  $key  Container lookup key
	 *
	 * @return  mixed
	 *
	 * @note    Method accepts additional params for special cases
	 * @since   1.0
	 */
	public static function get($key)
	{
		$args = func_get_args();

		switch ($key)
		{
			case 'repository' :
				return self::getRepository($args[1]);

			default :
				return self::$instance->getContainer()->get($key);
		}
	}
}
