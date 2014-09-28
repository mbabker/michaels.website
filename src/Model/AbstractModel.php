<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Model;

use BabDev\Website\Database\AbstractTable;

use Joomla\Database\DatabaseDriver;
use Joomla\Database\DatabaseQuery;
use Joomla\Model\AbstractDatabaseModel;
use Joomla\Registry\Registry;

/**
 * Base model class for the application
 *
 * @since  1.0
 */
abstract class AbstractModel extends AbstractDatabaseModel
{
	/**
	 * Internal memory based cache array of data.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected $cache = array();

	/**
	 * Context string for the model type.
	 *
	 * This is used to handle uniqueness when dealing with the getStoreId() method and caching data structures.
	 *
	 * @var    string
	 * @since  1.0
	 * @see    AbstractModel::getStoreId()
	 */
	protected $context = null;

	/**
	 * The extension the model belongs to
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $extension = null;

	/**
	 * The model (base) name
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $name = null;

	/**
	 * An internal cache for the last query used.
	 *
	 * @var    DatabaseQuery
	 * @since  1.0
	 */
	protected $query;

	/**
	 * Instantiate the model.
	 *
	 * @param   DatabaseDriver  $db     The database adapter.
	 * @param   Registry        $state  The model state.
	 *
	 * @since   1.0
	 */
	public function __construct(DatabaseDriver $db, Registry $state = null)
	{
		parent::__construct($db, $state);

		// Detect the extension name
		if (empty($this->extension))
		{
			// Get the fully qualified class name for the current object
			$fqcn = (get_class($this));

			// Strip the base namespace off
			$className = str_replace('Extensions\\', '', $fqcn);

			// Explode the remaining name into an array
			$classArray = explode('\\', $className);

			// Set the extension as the first object in this array
			$this->extension = $classArray[0];
		}

		// Set the view name
		if (empty($this->name))
		{
			$this->getName();
		}

		// Set the context if not already done
		if (is_null($this->context))
		{
			$this->context = strtolower($this->extension . '.' . $this->name);
		}
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.0
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the query for the list
		$query = $this->getListQuery();

		$items = $this->getList($query, $this->getStart(), $this->getState()->get('list.limit'));

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	/**
	 * Gets an array of objects from the results of database query.
	 *
	 * @param   DatabaseQuery  $query       The query.
	 * @param   integer        $limitstart  Offset.
	 * @param   integer        $limit       The number of records.
	 *
	 * @return  array  An array of results.
	 *
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	protected function getList($query, $limitstart = 0, $limit = 0)
	{
		return $this->getDb()->setQuery($query, $limitstart, $limit)->loadObjectList();
	}

	/**
	 * Returns a record count for the query.
	 *
	 * @param   DatabaseQuery  $query  The query.
	 *
	 * @return  integer  Number of rows for query.
	 *
	 * @since   1.0
	 */
	protected function getListCount(DatabaseQuery $query)
	{
		$query = clone $query;
		$query->clear('select')->clear('order')->clear('limit')->select('COUNT(*)');

		return $this->getDb()->setQuery($query)->loadResult();
	}

	/**
	 * Method to get a DatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  DatabaseQuery  A DatabaseQuery object to retrieve the data set.
	 *
	 * @since   1.0
	 */
	protected function getListQuery()
	{
		// Capture the last store id used.
		static $lastStoreId;

		// Compute the current store id.
		$currentStoreId = $this->getStoreId();

		// If the last store id is different from the current, refresh the query.
		if ($lastStoreId != $currentStoreId || empty($this->query))
		{
			$lastStoreId = $currentStoreId;
			$this->query = $this->getListQueryObject();
		}

		return $this->query;
	}

	/**
	 * Method to get a DatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  DatabaseQuery  A DatabaseQuery object to retrieve the data set.
	 *
	 * @since   1.0
	 */
	abstract protected function getListQueryObject();

	/**
	 * Method to get the model name
	 *
	 * @return  string  The name of the model
	 *
	 * @since   1.0
	 */
	public function getName()
	{
		if (empty($this->name))
		{
			// Get the fully qualified class name for the current object
			$fqcn = (get_class($this));

			// Explode the name into an array
			$classArray = explode('\\', $fqcn);

			// Get the last element from the array
			$class = array_pop($classArray);

			// Remove Model from the name and store it
			$this->name = str_replace('Model', '', $class);
		}

		return $this->name;
	}

	/**
	 * Method to get a JPagination object for the data set.
	 *
	 * @return  \JPagination  A JPagination object for the data set.
	 *
	 * @since   1.0
	 */
	public function getPagination()
	{
		return;

		// Create the pagination object.
		$limit = (int) $this->getState()->get('list.limit') - (int) $this->getState()->get('list.links');

		return new \JPagination($this->getTotal(), $this->getStart(), $limit);
	}

	/**
	 * Method to get the starting number of items for the data set.
	 *
	 * @return  integer  The starting number of items available in the data set.
	 *
	 * @since   1.0
	 */
	public function getStart()
	{
		$store = $this->getStoreId('getstart');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		$start = (int) $this->getState()->get('list.start');
		$limit = (int) $this->getState()->get('list.limit');
		$total = $this->getTotal();

		if ($start > $total - $limit)
		{
			$start = max(0, (int) (ceil($total / $limit) - 1) * $limit);
		}

		// Add the total to the internal cache.
		$this->cache[$store] = $start;

		return $this->cache[$store];
	}

	/**
	 * Method to get a store id based on the model configuration state.
	 *
	 * This is necessary because the model is used by the component and different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  An identifier string to generate the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.0
	 */
	protected function getStoreId($id = '')
	{
		// Add the list state to the store id.
		$id .= ':' . $this->getState()->get('list.start');
		$id .= ':' . $this->getState()->get('list.limit');
		$id .= ':' . $this->getState()->get('list.ordering');
		$id .= ':' . $this->getState()->get('list.direction');

		return md5($this->context . ':' . $id);
	}

	/**
	 * Method to get a table object
	 *
	 * @param   string  $name    The table name. Optional.
	 * @param   string  $suffix  The class suffix. Optional.
	 *
	 * @return  AbstractTable
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function getTable($name = '', $suffix = 'Table')
	{
		if (empty($name))
		{
			$name = $this->getName();
		}

		$namespace = str_replace('Model', 'Table', __NAMESPACE__);

		$class = $namespace . '\\' . ucfirst($name) . $suffix;

		if (!class_exists($class) && !($class instanceof AbstractTable))
		{
			throw new \RuntimeException(sprintf('Table class %s not found or is not an instance of AbstractTable.', $class));
		}

		return new $class($this->getDb());
	}

	/**
	 * Method to get the total number of items for the data set.
	 *
	 * @return  integer  The total number of items available in the data set.
	 *
	 * @since   1.0
	 */
	public function getTotal()
	{
		// Get a storage key.
		$store = $this->getStoreId('getTotal');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the total.
		$query = $this->getListQuery();

		$total = (int) $this->getListCount($query);

		// Add the total to the internal cache.
		$this->cache[$store] = $total;

		return $this->cache[$store];
	}
}
