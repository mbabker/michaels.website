<?php
/**
 * Users extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Users\Model;

use BabDev\Website\Model\AbstractModel;
use Joomla\Database\DatabaseQuery;

/**
 * Model class for querying user lists
 *
 * @since  1.0
 */
class ListsModel extends AbstractModel
{
	/**
	 * Method to get a DatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  DatabaseQuery  A DatabaseQuery object to retrieve the data set.
	 *
	 * @since   1.0
	 */
	protected function getListQueryObject()
	{
		$db    = $this->getDb();
		$query = $db->getQuery(true)
			->select('*')
			->from('#__users')
			->order('name ASC');

		return $query;
	}
}
