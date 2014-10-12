<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Entity;

use Doctrine\ORM\QueryBuilder;

/**
 * Repository for the User entity
 *
 * @since  1.0
 */
class UserRepository extends BaseRepository
{
	/**
	 * Sets the last login time for the user
	 *
	 * @param   User  $user  User entity to update
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setLastLogin(User $user)
	{
		$user->setLastLogin(new \DateTime('now', new \DateTimeZone('UTC')));
		$this->saveEntity($user);
	}

	/**
	 * Loads a User entity by the given username
	 *
	 * @param   string  $username  Username to search by
	 *
	 * @return  User|null  User entity if object found, null otherwise
	 */
	public function loadByUsername($username)
	{
		return $this->findOneBy(['username' => $username]);
	}

	/**
	 * Checks to ensure that a username and/or email is unique
	 *
	 * @param   array  $params  Params array; must contain either a 'email' or 'username' key
	 *
	 * @return  array
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException if the $params array does not contain a required key
	 */
	public function checkUniqueUsernameEmail(array $params)
	{
		$identifier = isset($params['email']) ? $params['email'] : isset($params['username']) ? $params['username'] : null;

		if (is_null(($identifier)))
		{
			throw new \InvalidArgumentException('The params array must contain a email or username key.');
		}

		$q = $this
			->createQueryBuilder($this->getTableAlias())
			->where('u.username = :identifier OR u.email = :identifier')
			->setParameter("identifier", $identifier)
			->getQuery();

		return $q->getResult();
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
		// Ensure that the string has a unique parameter identifier
		$unique = $this->generateRandomParameterName();
		$string = ($filter->strict) ? $filter->string : "%{$filter->string}%";

		$expr = $q->expr()->orX(
			$q->expr()->like('u.username', ':' . $unique),
			$q->expr()->like('u.email', ':' . $unique),
			$q->expr()->like('u.firstName', ':' . $unique),
			$q->expr()->like('u.lastName', ':' . $unique),
			$q->expr()->like('u.position', ':' . $unique)
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
		return [['u.name', 'ASC'], ['u.username', 'ASC']];
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
		return 'u';
	}
}
