<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website;

use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Application Factory
 *
 * @since  1.0
 */
abstract class Factory
{
	/**
	 * Fetch the DI container
	 *
	 * @return  \Joomla\DI\Container
	 *
	 * @since   1.0
	 */
	private static function getContainer()
	{
		return Application::getDIContainer();
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
	public static function getRepository($entity)
	{
		$repo = $entity . 'Repository';

		if (!class_exists($repo))
		{
			throw new \InvalidArgumentException('A valid repository class was not found.');
		}

		return new $repo(self::getContainer()->get('em'), new ClassMetadata($entity));
	}
}
