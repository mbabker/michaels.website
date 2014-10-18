<?php
/**
 * Users extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Categories\Model;

use BabDev\Website\Factory;
use BabDev\Website\Model\AbstractModel;

/**
 * Model class for querying extension categories
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
	public function getCategories()
	{
		/** @var \BabDev\Website\Entity\CategoryRepository $repo */
		$repo = Factory::get('repository', '\\BabDev\\Website\\Entity\\Category');

		return $repo->getCategoryList($this->getState()->get('category.extension'));
	}
}
