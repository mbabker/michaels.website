<?php
/**
 * Categories extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Categories\Model;

use BabDev\Website\Entity\Category;
use BabDev\Website\Factory;
use BabDev\Website\Model\AbstractModel;
use Joomla\Filter\OutputFilter;

/**
 * Model class for interfacing with a single category entity
 *
 * @since  1.0
 */
class CategoryModel extends AbstractModel
{
	/**
	 * Retrieve a single category
	 *
	 * @param   integer|null  $id  The user ID to retrieve or null to use the active user
	 *
	 * @return  \BabDev\Website\Entity\Category
	 *
	 * @since   1.0
	 */
	public function getCategory($id = null)
	{
		$id = is_null($id) ? $this->getState()->get('category.id') : $id;

		/** @var \BabDev\Website\Entity\CategoryRepository $repo */
		$repo = Factory::getRepository('\\BabDev\\Website\\Entity\\Category');

		return $repo->getEntity($id);
	}

	/**
	 * Save a user
	 *
	 * @param   integer  $id    The user ID to save
	 * @param   array    $data  The data to save to the user
	 *
	 * @return  \BabDev\Website\Entity\User
	 *
	 * @since   1.0
	 */
	public function save($id, array $data)
	{
		/** @var \BabDev\Website\Entity\CategoryRepository $repo */
		$repo = Factory::getRepository('\\BabDev\\Website\\Entity\\Category');

		/** @var \BabDev\Website\Entity\Category $category */
		$category = $repo->getEntity($id);

		// Before processing, ensure we have a valid alias
		if (!isset($data['alias']))
		{
			$data['alias'] = '';
		}

		if (trim($data['alias']) == '')
		{
			$data['alias'] = $data['title'];
		}

		$data['alias'] = OutputFilter::stringURLUnicodeSlug($data['alias']);

		if (trim(str_replace('-', '', $data['alias'])) == '')
		{
			$data['alias'] = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d-H-i-s');
		}

		foreach ($data as $key => $value)
		{
			$function = 'set' . ucfirst($key);

			$category->$function($value);
		}

		$category->setModifiedBy()
			->setDateModified()
			->setExtension($this->getState()->get('category.extension'));
		$repo->saveEntity($category);
	}
}
