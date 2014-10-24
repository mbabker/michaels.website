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
class Article extends BaseEntity
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
	 * Text
	 *
	 * @var    string
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="text", type="text", nullable=false)
	 */
	private $text;

	/**
	 * Object's category
	 *
	 * @var    Category
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="Category")
	 * @\Doctrine\ORM\Mapping\JoinColumn(name="category", referencedColumnName="id", nullable=true)
	 */
	private $category;

	/**
	 * Object parameters
	 *
	 * @var    array
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="params", type="array", nullable=true)
	 */
	private $params;

	/**
	 * Date the object starts publishing
	 *
	 * @var    \DateTime
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="publish_up", type="datetime", nullable=true)
	 */
	private $publishUp;

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
	 * Retrieve the object text
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
	 * Retrieve the object category
	 *
	 * @return  Category
	 *
	 * @since   1.0
	 */
	public function getCategory()
	{
		return $this->category;
	}

	/**
	 * Retrieve the object params
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getParams()
	{
		return $this->params;
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
	 * Set the object's text
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
	 * Set the object's category
	 *
	 * @param   Category  $category  Category to assign the object to
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setCategory(Category $category = null)
	{
		$this->category = $category;

		return $this;
	}

	/**
	 * Set the object's params
	 *
	 * @param   array  $params  Object params
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setParams(array $params = array())
	{
		$this->params = $params;

		return $this;
	}

	/**
	 * Set the time the article starts publishing
	 *
	 * @param   \DateTime  $date  Publish time
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setPublishUp(\DateTime $date)
	{
		$this->publishUp = !is_null($date) ? $date : new \DateTime('now', new \DateTimeZone('UTC'));

		return $this;
	}

	/**
	 * Set the time the article starts publishing
	 *
	 * @return  \DateTime
	 *
	 * @since   1.0
	 */
	public function getPublishUp()
	{
		return $this->publishUp;
	}
}
