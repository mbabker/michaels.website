<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Model;

use Joomla\Database\DatabaseQuery;

/**
 * Default model class for the application
 *
 * @since  1.0
 */
class DefaultModel extends AbstractModel
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
		$db = $this->getDb();
		$query = $db->getQuery(true);

		return $query;
	}
}
