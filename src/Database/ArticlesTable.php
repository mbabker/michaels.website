<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Database;

use Joomla\Database\DatabaseDriver;
use Joomla\Filter\OutputFilter;

/**
 * Table interface class for the #__articles table
 *
 * @property   integer  $id            Primary key
 * @property   string   $title         The article title
 * @property   string   $alias         The article alias
 * @property   string   $text          The article text
 * @property   string   $created_date  The created date
 *
 * @since      1.0
 */
class ArticlesTable extends AbstractTable
{
	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  A database connector object
	 *
	 * @since   1.0
	 */
	public function __construct(DatabaseDriver $db)
	{
		parent::__construct('#__articles', 'article_id', $db);
	}

	/**
	 * Load an article by alias.
	 *
	 * @param   string  $alias  The alias.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function loadByAlias($alias)
	{
		$check = $this->db->setQuery(
			$this->db->getQuery(true)
				->select('*')
				->from($this->tableName)
				->where('alias = ' . $this->db->quote($alias))
		)->loadObject();

		return ($check) ? $this->bind($check) : $this;
	}

	/**
	 * Method to perform sanity checks on the AbstractTable instance properties to ensure they are safe to store in the database.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 */
	public function check()
	{
		$errors = array();

		if (trim($this->alias) == '')
		{
			if (trim($this->title) == '')
			{
				throw new \InvalidArgumentException('An alias or a title is required.');
			}

			$this->alias = trim($this->title);
		}

		$this->alias = OutputFilter::stringURLUnicodeSlug($this->alias);

		return $this;
	}

	/**
	 * Method to store a row in the database from the AbstractTable instance properties.
	 *
	 * If a primary key value is set the row with that primary key value will be updated with the instance property values.  If no primary key value
	 * is set a new row will be inserted into the database with the properties from the AbstractTable instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function store($updateNulls = false)
	{
		if (!$this->created_date || $this->created_date == $this->db->getNullDate())
		{
			// New item - set an (arbitrary) created date..
			$this->created_date = (new \DateTime)->format($this->db->getDateFormat());
		}

		return parent::store($updateNulls);
	}
}
