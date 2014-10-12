<?php
/**
 * Users extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Users\Model;

use BabDev\Website\Factory;
use BabDev\Website\Model\AbstractModel;

/**
 * Model class for querying user lists
 *
 * @since  1.0
 */
class ListsModel extends AbstractModel
{
	/**
	 * Retrieve a list of users
	 *
	 * @return  \Doctrine\ORM\Tools\Pagination\Paginator
	 *
	 * @since   1.0
	 */
	public function getUsers()
	{
		/** @var \BabDev\Website\Entity\UserRepository $repo */
		$repo = Factory::getRepository('\\BabDev\\Website\\Entity\\User');

		return $repo->getEntities();
	}
}
