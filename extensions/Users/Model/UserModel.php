<?php
/**
 * Users extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Users\Model;

use BabDev\Website\Factory;
use BabDev\Website\Model\AbstractModel;

/**
 * Model class for interfacing with a single user entity
 *
 * @since  1.0
 */
class UserModel extends AbstractModel
{
	/**
	 * Retrieve a single user
	 *
	 * @param   integer|null  $id  The user ID to retrieve or null to use the active user
	 *
	 * @return  \BabDev\Website\Entity\User
	 *
	 * @since   1.0
	 */
	public function getUser($id = null)
	{
		$id = is_null($id) ? $this->getState()->get('user.id') : $id;

		/** @var \BabDev\Website\Entity\UserRepository $repo */
		$repo = Factory::getRepository('\\BabDev\\Website\\Entity\\User');

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
		/** @var \BabDev\Website\Entity\UserRepository $repo */
		$repo = Factory::getRepository('\\BabDev\\Website\\Entity\\User');

		/** @var \BabDev\Website\Entity\User $user */
		$user = $repo->getEntity($id);

		foreach ($data as $key => $value)
		{
			$function = 'set' . ucfirst($key);

			// If this is the password, hash it
			if ($key == 'password')
			{
				$value = password_hash($value, PASSWORD_BCRYPT);
			}

			$user->$function($value);
		}

		$repo->saveEntity($user);

		return $user;
	}
}
