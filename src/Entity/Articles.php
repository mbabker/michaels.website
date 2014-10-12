<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Entity;

/**
 * Article Entity
 *
 * @\Doctrine\ORM\Mapping\Table(name="articles")
 * @\Doctrine\ORM\Mapping\Entity
 */
class Articles
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
	 * Title
	 *
	 * @var    string
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="title", type="string", length=250, nullable=false)
	 */
	private $title;

	/**
	 * Alias
	 *
	 * @var    string
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="alias", type="string", length=250, nullable=false)
	 */
	private $alias;

	/**
	 * Text
	 *
	 * @var    string
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="text", type="text", nullable=false)
	 */
	private $text;

	/**
	 * Created Date
	 *
	 * @var    \DateTime
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="created_date", type="datetime", nullable=false)
	 */
	private $createdDate;

	/**
	 * User ID
	 *
	 * @var    User
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="User")
	 * @\Doctrine\ORM\Mapping\JoinColumn(name="owner", referencedColumnName="id")
	 */
	private $owner;

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
	 * Retrieve the entity title
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Retrieve the entity alias
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getAlias()
	{
		return $this->alias;
	}

	/**
	 * Retrieve the entity text
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * Retrieve the entity's creation date
	 *
	 * @return  \DateTime
	 *
	 * @since   1.0
	 */
	public function getCreatedDate()
	{
		return $this->createdDate;
	}

	/**
	 * Retrieve the entity's owner
	 *
	 * @return  User
	 *
	 * @since   1.0
	 */
	public function getOwner()
	{
		return $this->owner;
	}

	/**
	 * Set the entity's title
	 *
	 * @param   string  $title  Title text
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Set the entity's alias
	 *
	 * @param   string  $alias  Alias text
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setAlias($alias)
	{
		$this->alias = $alias;

		return $this;
	}

	/**
	 * Set the entity's text
	 *
	 * @param   string  $text  Text
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setText($text)
	{
		$this->text = $text;

		return $this;
	}

	/**
	 * Set the entity's creation date
	 *
	 * @param   \DateTime  $date  Creation date
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setCreatedDate(\DateTime $date = null)
	{
		$this->createdDate = !is_null($date) ? $date : new \DateTime();

		return $this;
	}

	/**
	 * Set the entity's owner
	 *
	 * @param   User  $owner  Owner entity object
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setOwner(User $owner)
	{
		$this->owner = $owner;

		return $this;
	}
}
