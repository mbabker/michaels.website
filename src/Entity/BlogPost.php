<?php

namespace BabDev\Website\Entity;

/**
 * Blog post entity.
 */
class BlogPost
{
    /**
     * @var string
     */
    private $author = '';

    /**
     * @var string
     */
    private $category = '';

    /**
     * @var \DateTime
     */
    private $publish_up;

    /**
     * @var \DateTime
     */
    private $date_modified;

    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $alias = '';

    /**
     * @var string
     */
    private $text = '';

    /**
     * @var string
     */
    private $preview = '';

    /**
     * @var string
     */
    private $image = '';

    /**
     * @var string
     */
    private $previous = '';

    /**
     * @var string
     */
    private $next = '';

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return \DateTime|null
     */
    public function getPublishUp()
    {
        return $this->publish_up;
    }

    /**
     * @param \DateTime $publish_up
     */
    public function setPublishUp(\DateTime $publish_up)
    {
        $this->publish_up = $publish_up;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }

    /**
     * @param \DateTime $date_modified
     */
    public function setDateModified(\DateTime $date_modified)
    {
        $this->date_modified = $date_modified;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getPreview(): string
    {
        return $this->preview;
    }

    /**
     * @param string $preview
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getPrevious(): string
    {
        return $this->previous;
    }

    /**
     * @param string $previous
     */
    public function setPrevious($previous)
    {
        $this->previous = $previous;
    }

    /**
     * @return string
     */
    public function getNext(): string
    {
        return $this->next;
    }

    /**
     * @param string $next
     */
    public function setNext($next)
    {
        $this->next = $next;
    }

    /**
     * @return bool
     */
    public function hasPrevious(): bool
    {
        return $this->getPrevious() !== '';
    }

    /**
     * @return bool
     */
    public function hasNext(): bool
    {
        return $this->getNext() !== '';
    }
}
