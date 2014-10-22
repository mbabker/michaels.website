<?php
/**
 * Articles extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Articles\Model;

use BabDev\Website\Factory;
use BabDev\Website\Model\AbstractModel;
use Joomla\Filter\OutputFilter;

/**
 * Model class for interfacing with a single article entity
 *
 * @since  1.0
 */
class ArticleModel extends AbstractModel
{
	/**
	 * Retrieve a single category
	 *
	 * @param   integer|null  $id  The user ID to retrieve or null to use the active user
	 *
	 * @return  \BabDev\Website\Entity\Article
	 *
	 * @since   1.0
	 */
	public function getArticle($id = null)
	{
		$id = is_null($id) ? $this->getState()->get('article.id') : $id;

		/** @var \BabDev\Website\Entity\ArticleRepository $repo */
		$repo = Factory::get('repository', '\\BabDev\\Website\\Entity\\Article');

		return $repo->getEntity($id);
	}

	/**
	 * Save an article
	 *
	 * @param   integer  $id    The user ID to save
	 * @param   array    $data  The data to save to the user
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function save($id, array $data)
	{
		/** @var \BabDev\Website\Entity\ArticleRepository $repo */
		$repo = Factory::get('repository', '\\BabDev\\Website\\Entity\\Article');

		/** @var \BabDev\Website\Entity\Article $article */
		$article = $repo->getEntity($id);

		/** @var \BabDev\Website\Entity\CategoryRepository $categoryRepo */
		$categoryRepo = Factory::get('repository', '\\BabDev\\Website\\Entity\\Category');

		/** @var \BabDev\Website\Entity\Category $category */
		$category = $categoryRepo->getEntity($data['category']);

		/** @var \BabDev\Website\Entity\UserRepository $userRepo */
		$userRepo = Factory::get('repository', '\\BabDev\\Website\\Entity\\User');

		/** @var \BabDev\Website\Entity\User $user */
		$user = $userRepo->getEntity($data['user']);

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

		$data['text'] = trim($data['text']);

		// Strip data out of the array
		$isNew = $data['isNew'];

		unset($data['category']);
		unset($data['isNew']);
		unset($data['user']);

		foreach ($data as $key => $value)
		{
			$function = 'set' . ucfirst($key);

			$article->$function($value);
		}

		if ($isNew)
		{
			$article->setCreatedBy($user)
				->setDateAdded();
		}

		$article->setModifiedBy($user)
			->setDateModified()
			->setCategory($category);
		$repo->saveEntity($article);
	}
}
