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

/**
 * Model class for querying articles
 *
 * @since  1.0
 */
class ListsModel extends AbstractModel
{
	/**
	 * Retrieve a list of articles
	 *
	 * @param   boolean  $isPublished  Flag if only published articles should be returned
	 *
	 * @return  \Doctrine\ORM\Tools\Pagination\Paginator
	 *
	 * @since   1.0
	 */
	public function getArticles($isPublished = false)
	{
		/** @var \BabDev\Website\Entity\ArticleRepository $repo */
		$repo = Factory::get('repository', '\\BabDev\\Website\\Entity\\Article');

		return $repo->getArticleList($this->getState()->get('category.alias', ''), $isPublished);
	}
}
