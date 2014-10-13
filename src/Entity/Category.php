<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Entity;

/**
 * Category Entity
 *
 * @since  1.0
 *
 * @\Doctrine\ORM\Mapping\Table(name="categories")
 * @\Doctrine\ORM\Mapping\Entity(repositoryClass="BabDev\Website\Entity\CategoryRepository")
 */
class Category extends BaseEntity
{
	/**
	 * Primary Key
	 *
	 * @var    integer
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="id", type="integer", nullable=false)
	 * @\Doctrine\ORM\Mapping\Id
	 * @\Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
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
	 * Extension to which the category is "owned" by
	 *
	 * @var    string
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="extension", type="string", length=50, nullable=false)
	 */
	private $extension;

	/**
	 * Magic method called when an object is cloned
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function __clone()
	{
		$this->id = null;
	}

	/**
	 * Retrieve the object ID
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
	 * Retrieve the object title
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
	 * Retrieve the object alias
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
	 * Retrieve the object's extension
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getExtension()
	{
		return $this->extension;
	}

	/**
	 * Set the object's title
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
	 * Set the object's alias
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
	 * Set the object's extension
	 *
	 * @param   string  $extension  "Owning" extension
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setExtension($extension)
	{
		$this->extension = $extension;

		return $this;
	}
}
