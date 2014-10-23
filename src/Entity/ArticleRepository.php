<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Entity;

/**
 * Repository for the Article entity
 *
 * @since  1.0
 */
class ArticleRepository extends BaseRepository
{
	/**
	 * Retrieve a list of articles
	 *
	 * @param   string   $category  An optional category alias to filter on
	 * @param   string   $search    An optional title to search for
	 * @param   integer  $limit     A limit to the number of items to retrieve, defaults to 10
	 * @param   integer  $start     The first row to start the lookup at, defaults to 0 for the first row in the return
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getArticleList($category = '', $search = '', $limit = 10, $start = 0)
	{
		$q = $this->createQueryBuilder($this->getTableAlias());
		$q->select('a, c, uc, um')
			->join('a.category', 'c')
			->join('a.createdBy', 'uc')
			->join('a.modifiedBy', 'um');

		if (!empty($category))
		{
			$q->where($q->expr()->eq('c.alias', ':category'))
				->setParameter('category', $category);
		}

		if (!empty($search))
		{
			$q->where($q->expr()->like('a.title', ':search'))
				->setParameter('search', "{$search}%");
		}

		$q->orderBy('a.title');

		if (!empty($limit))
		{
			$q->setFirstResult($start)
				->setMaxResults($limit);
		}

		return $q->getQuery()->getArrayResult();
	}

	/**
	 * Get a single entity
	 *
	 * @param   integer  $id  ID to lookup the entity by
	 *
	 * @return  Article
	 *
	 * @since   1.0
	 */
	public function getEntity($id = 0)
	{
		$entity = parent::getEntity($id);

		if (is_null($entity))
		{
			return new Article;
		}

		return $entity;
	}

	/**
	 * Loads an Article entity searching by alias
	 *
	 * @param   string  $alias          Article alias to search by
	 * @param   string  $categoryAlias  Category alias to search by
	 *
	 * @return  Article|null  Article entity if object found, null otherwise
	 */
	public function loadByAlias($alias, $categoryAlias)
	{
		$q = $this->createQueryBuilder($this->getTableAlias());
		$q->select('a, c, uc, um')
			->join('a.category', 'c')
			->join('a.createdBy', 'uc')
			->join('a.modifiedBy', 'um')
			->where($q->expr()->eq('a.alias', ':articleAlias'))
			->setParameter('articleAlias', $alias)
			->andWhere($q->expr()->eq('c.alias', ':catAlias'))
			->setParameter('catAlias', $categoryAlias);

		return $q->getQuery()->getOneOrNullResult();
	}

	/**
	 * Adds a catch all WHERE clause for the query
	 *
	 * @param   \Doctrine\ORM\QueryBuilder  $q       The QueryBuilder object to append the clauses to
	 * @param   array                       $filter  Filter to process
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	protected function addCatchAllWhereClause(&$q, $filter)
	{
		// Ensure that the string has a unique parameter identifier
		$unique = $this->generateRandomParameterName();
		$string = ($filter->strict) ? $filter->string : "%{$filter->string}%";

		$expr = $q->expr()->orX(
			$q->expr()->like('a.title', ':' . $unique)
		);

		if ($filter->not)
		{
			$expr = $q->expr()->not($expr);
		}

		return [$expr, ["$unique" => $string]];
	}

	/**
	 * Returns the default order for the query
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	protected function getDefaultOrder()
	{
		return [['a.title', 'ASC']];
	}

	/**
	 * Returns the base table alias
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getTableAlias()
	{
		return 'a';
	}
}
