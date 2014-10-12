<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Base Entity Repository
 *
 * @since  1.0
 */
class BaseRepository extends EntityRepository
{
	/**
	 * Current authenticated user
	 *
	 * @var    User
	 * @since  1.0
	 */
	protected $currentUser;

	/**
	 * Set the current user (i.e. from security context) for use within repositories
	 *
	 * @param   User  $user  User entity
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setCurrentUser(User $user)
	{
		$this->currentUser = ($user instanceof User) ? $user : new User;
	}

	/**
	 * Get a single entity
	 *
	 * @param   integer  $id  ID to lookup the entity by
	 *
	 * @return  null|$this  The entity instance or null if the entity can not be found.
	 */
	public function getEntity($id = 0)
	{
		return $this->find($id);
	}

	/**
	 * Get a list of entities
	 *
	 * @param   array  $args  Optional array of arguments to filter entities by Q
	 *
	 * @return  Paginator
	 *
	 * @since   1.0
	 */
	public function getEntities($args = array())
	{
		$alias = $this->getTableAlias();
		$q     = $this->createQueryBuilder($alias)->select($alias);

		$this->buildClauses($q, $args);
		$query = $q->getQuery();

		if (isset($args['hydration_mode']))
		{
			$mode = strtoupper($args['hydration_mode']);
			$query->setHydrationMode(constant("\\Doctrine\\ORM\\Query::$mode"));
		}

		return new Paginator($query);
	}

	/**
	 * Checks for a unique alias in an entity
	 *
	 * @param   string       $alias   The alias to check for
	 * @param   object|null  $entity  An optional Entity object to filter for an object ID from
	 *
	 * @return  integer  Number of results with a similar alias
	 *
	 * @since   1.0
	 */
	public function checkUniqueAlias($alias, $entity = null)
	{
		$q = $this->createQueryBuilder('e')
			->select('count(e.id) as aliasCount')
			->where('e.alias = :alias')
			->setParameter('alias', $alias);

		if (!empty($entity))
		{
			$q->andWhere('e.id != :id')
				->setParameter('id', $entity->getId());
		}

		$results = $q->getQuery()->getSingleResult();

		return $results['aliasCount'];
	}

	/**
	 * Save an entity through the repository
	 *
	 * @param   object   $entity  The entity object
	 * @param   boolean  $flush   True by default; use false if persisting in batches
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function saveEntity($entity, $flush = true)
	{
		$this->_em->persist($entity);

		if ($flush)
		{
			$this->_em->flush();
		}
	}

	/**
	 * Persist an array of entities
	 *
	 * @param   array  $entities  An array of entities
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function saveEntities($entities)
	{
		// Iterate over the results so the events are dispatched on each delete
		$batchSize = 20;

		foreach ($entities as $k => $entity)
		{
			$this->saveEntity($entity, false);

			if ((($k + 1) % $batchSize) === 0)
			{
				$this->_em->flush();
			}
		}

		$this->_em->flush();
	}

	/**
	 * Delete an entity through the repository
	 *
	 * @param   object   $entity  The entity object
	 * @param   boolean  $flush   True by default; use false if persisting in batches
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function deleteEntity($entity, $flush = true)
	{
		//delete entity
		$this->_em->remove($entity);

		if ($flush)
		{
			$this->_em->flush();
		}
	}

	/**
	 * Build additional query clauses for a lookup
	 *
	 * @param   QueryBuilder  $q     The QueryBuilder object to append the clauses to
	 * @param   array         $args  The arguments to append
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function buildClauses(QueryBuilder &$q, array $args)
	{
		$this->buildWhereClause($q, $args);
		$this->buildOrderByClause($q, $args);
		$this->buildLimiterClauses($q, $args);
	}

	/**
	 * Build a query's WHERE clauses
	 *
	 * @param   QueryBuilder  $q     The QueryBuilder object to append the clauses to
	 * @param   array         $args  The arguments to append
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function buildWhereClause(&$q, array $args)
	{
		$filter  = array_key_exists('filter', $args) ? $args['filter'] : '';
		$string  = '';

		if (!empty($filter))
		{
			if (is_array($filter))
			{
				if (!empty($filter['force']))
				{
					if (is_array($filter['force']))
					{
						// Defined columns with keys of column, expr, value
						$forceParameters  = array();
						$forceExpressions = $q->expr()->andX();

						foreach ($filter['force'] as $f)
						{
							list($expr, $parameters) = $this->getFilterExpr($q, $f);

							$forceExpressions->add($expr);

							if (is_array($parameters))
							{
								$forceParameters = array_merge($forceParameters, $parameters);
							}
						}
					}
					else
					{
						// String so parse as advanced search
						list($forceExpressions, $forceParameters) = $this->addAdvancedSearchWhereClause($q, $filter['force']);
					}
				}

				if (!empty($filter['string']))
				{
					$string = $filter['string'];
				}
			}
			else
			{
				$string = $filter;
			}

			// Parse the filter if set
			if (!empty($string) || !empty($forceExpressions))
			{
				if (!empty($string))
				{
					// Remove wildcards passed by user
					if (strpos($string, '%') !== false)
					{
						$string = str_replace('%', '', $string);
					}

					list($expressions, $parameters) = $this->addAdvancedSearchWhereClause($q, $string);

					if (!empty($forceExpressions))
					{
						$expressions->add($forceExpressions);
						$parameters = array_merge($parameters, $forceParameters);
					}
				}
				elseif (!empty($forceExpressions))
				{
					// We do not have a user search but have some required filters
					$expressions = $forceExpressions;
					$parameters  = $forceParameters;
				}

				$filterCount = ($expressions instanceof \Countable) ? count($expressions) : count($expressions->getParts());

				if (!empty($filterCount))
				{
					$q->where($expressions)
						->setParameters($parameters);
				}
			}
		}
	}

	/**
	 * Builds the query expression for the given filter
	 *
	 * @param   QueryBuilder  $q       The QueryBuilder object to append the clauses to
	 * @param   array         $filter  Filter to process
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	protected function getFilterExpr(QueryBuilder &$q, array $filter)
	{
		$unique    = $this->generateRandomParameterName();
		$func      = (!empty($filter['operator'])) ? $filter['operator'] : $filter['expr'];
		$parameter = false;

		if (in_array($func, array('isNull', 'isNotNull')))
		{
			$expr = $q->expr()->{$func}($filter['column']);
		}
		elseif (in_array($func, array('in', 'notIn')))
		{
			$expr = $q->expr()->{$func}($filter['column'], $filter['value']);
		}
		else
		{
			if (isset($filter['strict']) && !$filter['strict'])
			{
				$filter['value'] = "%{$filter['value']}%";
			}

			$expr      = $q->expr()->{$func}($filter['column'], ':' . $unique);
			$parameter = array($unique => $filter['value']);
		}

		if (!empty($filter['not']))
		{
			$expr = $q->expr()->not($expr);
		}

		return array($expr, $parameter);
	}

	/**
	 * Adds a catch all WHERE clause for the query
	 *
	 * @param   QueryBuilder  $q       The QueryBuilder object to append the clauses to
	 * @param   array         $filter  Filter to process
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	protected function addCatchAllWhereClause(&$q, $filter)
	{
		return [false, array()];
	}

	/**
	 * Processes advanced search filters for the WHERE clause
	 *
	 * @param   QueryBuilder  $qb      The QueryBuilder object to append the clauses to
	 * @param   array         $filter  Filter to process
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	protected function addAdvancedSearchWhereClause(&$qb, $filters)
	{
		if (isset($filters->root))
		{
			//function is determined by the second clause type
			$type         = (isset($filters->root[1])) ? $filters->root[1]->type : $filters->root[0]->type;
			$parseFilters =& $filters->root;
		}
		elseif (isset($filters->children))
		{
			$type         = (isset($filters->children[1])) ? $filters->children[1]->type : $filters->children[0]->type;
			$parseFilters =& $filters->children;
		}
		else
		{
			$type         = (isset($filters[1])) ? $filters[1]->type : $filters[0]->type;
			$parseFilters =& $filters;
		}

		$parameters  = array();
		$expressions = $qb->expr()->{"{$type}X"}();

		foreach ($parseFilters as $f)
		{
			if (isset($f->children))
			{
				list($expr, $params) = $this->addAdvancedSearchWhereClause($qb, $f);
			}
			else
			{
				if (!empty($f->command))
				{
					// Treat the command:string as if its a single word
					$f->string = $f->command . ":" . $f->string;
					$f->not    = false;
					$f->strict = true;
					list($expr, $params) = $this->addCatchAllWhereClause($qb, $f);
				}
				else
				{
					list($expr, $params) = $this->addCatchAllWhereClause($qb, $f);
				}
			}

			if (!empty($params))
			{
				$parameters = array_merge($parameters, $params);
			}

			if (!empty($expr))
			{
				$expressions->add($expr);
			}
		}

		return [$expressions, $parameters];
	}

	/**
	 * Build a query's ORDER BY clauses
	 *
	 * @param   QueryBuilder  $q     The QueryBuilder object to append the clauses to
	 * @param   array         $args  The arguments to append
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function buildOrderByClause(&$q, array $args)
	{
		$orderBy    = array_key_exists('orderBy', $args) ? $args['orderBy'] : '';
		$orderByDir = array_key_exists('orderByDir', $args) ? $args['orderByDir'] : '';

		if (empty($orderBy))
		{
			$defaultOrder = $this->getDefaultOrder();

			foreach ($defaultOrder as $order)
			{
				$q->addOrderBy($order[0], $order[1]);
			}
		}
		else
		{
			// Add direction after each column
			$parts = explode(',', $orderBy);

			foreach ($parts as $order)
			{
				$q->addOrderBy($order, $orderByDir);
			}
		}
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
		return array();
	}

	/**
	 * Build a query's LIMIT statement
	 *
	 * @param   QueryBuilder  $q     The QueryBuilder object to append the clauses to
	 * @param   array         $args  The arguments to append
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function buildLimiterClauses(&$q, array $args)
	{
		$start = array_key_exists('start', $args) ? $args['start'] : 0;
		$limit = array_key_exists('limit', $args) ? $args['limit'] : 30;

		if (!empty($limit))
		{
			$q->setFirstResult($start)
				->setMaxResults($limit);
		}
	}

	/**
	 * Generates a random parameter name
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	protected function generateRandomParameterName()
	{
		$alpha_numeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

		return substr(str_shuffle($alpha_numeric), 0, 8);
	}

	/**
	 * Adds a catch all WHERE clause for the query
	 *
	 * @param   QueryBuilder  $q        The QueryBuilder object to append the clauses to
	 * @param   array         $filter   Filter to process
	 * @param   array         $columns  The columns to filter on
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	protected function addStandardCatchAllWhereClause(&$q, $filter, array $columns)
	{
		$unique = $this->generateRandomParameterName(); //ensure that the string has a unique parameter identifier
		$string = ($filter->strict) ? $filter->string : "%{$filter->string}%";

		$expr = $q->expr()->orX();

		foreach ($columns as $col)
		{
			$expr->add(
				$q->expr()->like($col, ":$unique")
			);
		}

		if ($filter->not)
		{
			$expr = $q->expr()->not($expr);
		}

		return array(
			$expr,
			array("$unique" => $string)
		);
	}

	/**
	 * Returns a andX Expr() that takes into account isPublished, publishUp and publishDown dates
	 *
	 * The Expr() sets a :now parameter that must be set in the calling function
	 *
	 * @param   QueryBuilder  $q      The QueryBuilder object to add the expressions to
	 * @param   string        $alias  The table alias to filter for
	 *
	 * @return  Query\Expr\Andx
	 *
	 * @since   1.0
	 */
	public function getPublishedByDateExpression(QueryBuilder $q, $alias = null)
	{
		$alias = is_null($alias) ? $this->getTableAlias() : $alias;

		return $q->expr()->andX(
			$q->expr()->eq("$alias.isPublished", true),
			$q->expr()->orX(
				$q->expr()->isNull("$alias.publishUp"),
				$q->expr()->gte("$alias.publishUp", ':now')
			),
			$q->expr()->orX(
				$q->expr()->isNull("$alias.publishDown"),
				$q->expr()->lte("$alias.publishDown", ':now')
			)
		);
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
		return 'e';
	}

	/**
	 * Gets the properties of an ORM entity
	 *
	 * @param   object  $entityClass        The Entity to process
	 * @param   boolean  $convertCamelCase  Flag to convert the columns from underscored to camelCase
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getBaseColumns($entityClass, $convertCamelCase = false)
	{
		static $baseCols = array();

		if (empty($baseCols[$entityClass]))
		{
			// Get a list of properties from the entity
			$entity  = new $entityClass;
			$reflect = new \ReflectionClass($entity);
			$props   = $reflect->getProperties();

			if ($parentClass = $reflect->getParentClass())
			{
				$parentProps = $parentClass->getProperties();
				$props       = array_merge($parentProps, $props);
			}

			$baseCols[$entityClass] = array();

			foreach ($props as $p)
			{
				if (!in_array($p->name, $baseCols[$entityClass]))
				{
					$n = $p->name;

					if ($convertCamelCase)
					{
						$n = preg_replace('/(?<=\\w)(?=[A-Z])/', "_$1", $n);
						$n = strtolower($n);
					}

					$baseCols[$entityClass][] = $n;
				}
			}
		}

		return $baseCols[$entityClass];
	}

	/**
	 * Examines the arguments passed to getEntities and converts ORM properties to DBAL column names
	 *
	 * @param   object  $entityClass  The Entity to process
	 * @param   array   $args         An optional array of arguments to process
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function convertOrmProperties($entityClass, array $args)
	{
		$properties = $this->getBaseColumns($entityClass);

		// Check force filters
		if (isset($args['filter']['force']) && is_array($args['filter']['force']))
		{
			foreach ($args['filter']['force'] as $k => &$f)
			{
				$col   = $f['column'];
				$alias = '';

				if (strpos($col, '.') !== false)
				{
					list($alias, $col) = explode('.', $col);
				}

				if (in_array($col, $properties))
				{
					$col = preg_replace('/(?<=\\w)(?=[A-Z])/', "_$1", $col);
					$col = strtolower($col);
				}

				$f['column'] = (!empty($alias)) ? $alias . '.' . $col : $col;
			}
		}

		// Check order by
		if (isset($args['order']))
		{
			if (is_array($args['order']))
			{
				foreach ($args['order'] as &$o)
				{
					$alias = '';

					if (strpos($o, '.') !== false)
					{
						list($alias, $o) = explode('.', $o);
					}

					if (in_array($o, $properties))
					{
						$o = preg_replace('/(?<=\\w)(?=[A-Z])/', "_$1", $o);
						$o = strtolower($o);
					}

					$o = (!empty($alias)) ? $alias . '.' . $o : $o;
				}
			}
		}

		return $args;
	}
}
