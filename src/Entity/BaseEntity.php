<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Entity;

/**
 * Common Base Entity object
 *
 * @since  1.0
 *
 * @\Doctrine\ORM\Mapping\MappedSuperclass
 * @\Doctrine\ORM\Mapping\HasLifecycleCallbacks
 * @Serializer\ExclusionPolicy("all")
 */
class BaseEntity
{
	/**
	 * Object's published status
	 *
	 * @var    boolean
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="published", type="boolean")
	 */
	private $published = true;

	/**
	 * Date an object was added
	 *
	 * @var    \DateTime
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="date_added", type="datetime", nullable=true)
	 */
	private $dateAdded = null;

	/**
	 * User the object was created by
	 *
	 * @var    User
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="User", cascade={"persist"})
	 * @\Doctrine\ORM\Mapping\JoinColumn(name="created_by", referencedColumnName="id", nullable=true)
	 */
	private $createdBy;

	/**
	 * Date an object was modified
	 *
	 * @var    \DateTime
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\Column(name="date_modified", type="datetime", nullable=true)
	 */
	private $dateModified;

	/**
	 * User the object was modified by
	 *
	 * @var    User
	 * @since  1.0
	 *
	 * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="User", cascade={"persist"})
	 * @\Doctrine\ORM\Mapping\JoinColumn(name="modified_by", referencedColumnName="id", nullable=true)
	 */
	private $modifiedBy;

	/**
	 * Check publish status with option to check against category, publish up and down dates
	 *
	 * @param   boolean  $checkPublishStatus   Flag to check an object's published status
	 * @param   boolean  $checkCategoryStatus  Flag to check the published status of an object's category if one exists
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function isPublished($checkPublishStatus = true, $checkCategoryStatus = true)
	{
		if ($checkPublishStatus && method_exists($this, 'getPublishUp'))
		{
			$status = $this->getPublishStatus();

			if ($status == 'published')
			{
				// Check to see if there is a category to check
				if ($checkCategoryStatus && method_exists($this, 'getCategory'))
				{
					$category = $this->getCategory();

					if ($category !== null && !$category->isPublished())
					{
						return false;
					}
				}
			}

			return $status == 'published';
		}

		return $this->getPublished();
	}

	/**
	 * Set the time the object was added
	 *
	 * @param   \DateTime  $date  The added time
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setDateAdded(\DateTime $date = null)
	{
		$this->dateAdded = !is_null($date) ? $date : new \DateTime('now', new \DateTimeZone('UTC'));

		return $this;
	}

	/**
	 * Get the time the object was added
	 *
	 * @return  \DateTime
	 *
	 * @since   1.0
	 */
	public function getDateAdded()
	{
		return $this->dateAdded;
	}

	/**
	 * Set the time the object was modified
	 *
	 * @param   \DateTime  $date  The modified time
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setDateModified(\DateTime $date = null)
	{
		$this->dateModified = !is_null($date) ? $date : new \DateTime('now', new \DateTimeZone('UTC'));

		return $this;
	}

	/**
	 * Get the time the object was modified
	 *
	 * @return  \DateTime
	 *
	 * @since   1.0
	 */
	public function getDateModified()
	{
		return $this->dateModified;
	}

	/**
	 * Set the user who created the object
	 *
	 * @param   User  $user  Entity object containing the user who created the object
	 *
	 * @return  $this
	 *
	 * @since   1.0
\	 */
	public function setCreatedBy(User $user = null)
	{
		$this->createdBy = $user;

		return $this;
	}

	/**
	 * Get the user who created the object
	 *
	 * @return  User
	 *
	 * @since   1.0
	 */
	public function getCreatedBy()
	{
		return $this->createdBy;
	}

	/**
	 * Set the user who last modified the object
	 *
	 * @param   User  $user  Entity object containing the user who last modified the object
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setModifiedBy(User $user = null)
	{
		$this->modifiedBy = $user;

		return $this;
	}

	/**
	 * Get the user who last modified the object
	 *
	 * @return  User
	 *
	 * @since   1.0
	 */
	public function getModifiedBy()
	{
		return $this->modifiedBy;
	}

	/**
	 * Set the item's published status
	 *
	 * @param   boolean  $published  True if published
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 */
	public function setPublished($published)
	{
		$this->published = $published;

		return $this;
	}

	/**
	 * Get the item's published status
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function getPublished()
	{
		return $this->published;
	}

	/**
	 * Check the publish status of an entity based on publish up and down datetimes
	 *
	 * @return  string  Status string, may be one of the following: early, expired, published, unpublished
	 *
	 * @since   1.0
	 */
	public function getPublishStatus()
	{
		$current = new \DateTime('now', new \DateTimeZone('UTC'));

		if (!$this->isPublished(false))
		{
			return 'unpublished';
		}

		$status = 'published';

		if (method_exists($this, 'getPublishUp'))
		{
			$up = $this->getPublishUp();

			if (!empty($up) && $current <= $up)
			{
				$status = 'pending';
			}
		}

		if (method_exists($this, 'getPublishDown'))
		{
			$down = $this->getPublishDown();

			if (!empty($down) && $current >= $down)
			{
				$status = 'expired';
			}
		}

		return $status;
	}

	/**
	 * Check if this is a new object
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function isNew()
	{
		$id = $this->getId();

		return empty($id);
	}
}
