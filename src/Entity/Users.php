<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Entity;

/**
 * User Entity
 *
 * @\Doctrine\ORM\Mapping\Table(name="users")
 * @\Doctrine\ORM\Mapping\Entity
 */
class Users
{
	/**
	 * Primary Key
	 *
	 * @var    integer
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="id", type="integer", nullable=false)
	 * @\Doctrine\ORM\Mapping\Id
	 * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * User's Full Name
	 *
	 * @var    string
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="name", type="string", length=255, nullable=false)
	 */
	private $name;

	/**
	 * User's Username
	 *
	 * @var    string
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="username", type="string", length=25, nullable=false, unique=true)
	 */
	private $username;

	/**
	 * User's Hashed Password
	 *
	 * @var    string
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="password", type="string", length=64, nullable=false)
	 */
	private $password;

	/**
	 * User's E-mail Address
	 *
	 * @var    string
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="email", type="string", length=100, nullable=false, unique=true)
	 */
	private $email;

	/**
	 * Retrieve the entity ID
	 *
	 * @return  integer
	 *
	 * @since   1.0
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Retrieve the entity name
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Retrieve the entity username
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Retrieve the entity password
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Retrieve the entity e-mail
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Set the entity's name
	 *
	 * @param   string  $name  Name
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Set the entity's username
	 *
	 * @param   string  $username  Username
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setUsername($username)
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * Set the entity's password
	 *
	 * @param   string  $password  Hashed password
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setPassword($password)
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Set the entity's e-mail address
	 *
	 * @param   string  $email  E-mail address
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}
}
