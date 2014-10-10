<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Model;

use Doctrine\ORM\EntityManager;

use Joomla\Model\AbstractModel as BaseModel;
use Joomla\Registry\Registry;

/**
 * Base model class for the application
 *
 * @since  1.0
 */
abstract class AbstractModel extends BaseModel
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
	 * The EntityManager object
	 *
	 * @var    EntityManager
	 * @since  1.0
	 */
	private $em;

	/**
	 * The model (base) name
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $name = null;

	/**
	 * Instantiate the model.
	 *
	 * @param   EntityManager  $em     The EntityManager object.
	 * @param   Registry       $state  The model state.
	 *
	 * @since   1.0
	 */
	public function __construct(EntityManager $em, Registry $state = null)
	{
		$this->em = $em;

		parent::__construct($state);

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
	 * Retrieve the EntityManager object
	 *
	 * @return  EntityManager
	 *
	 * @since   1.0
	 */
	public function getEntityManager()
	{
		return $this->em;
	}

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
}
