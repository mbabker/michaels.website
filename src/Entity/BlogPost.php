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

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category)
    {
        $this->category = $category;
    }

    public function getPublishUp(): ?\DateTime
    {
        return $this->publish_up;
    }

    public function setPublishUp(\DateTime $publish_up)
    {
        $this->publish_up = $publish_up;
    }

    public function getDateModified(): ?\DateTime
    {
        return $this->date_modified;
    }

    public function setDateModified(\DateTime $date_modified)
    {
        $this->date_modified = $date_modified;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias)
    {
        $this->alias = $alias;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    public function getPreview(): string
    {
        return $this->preview;
    }

    public function setPreview(string $preview)
    {
        $this->preview = $preview;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image)
    {
        $this->image = $image;
    }

    public function getPrevious(): string
    {
        return $this->previous;
    }

    public function setPrevious(string $previous)
    {
        $this->previous = $previous;
    }

    public function getNext(): string
    {
        return $this->next;
    }

    public function setNext(string $next)
    {
        $this->next = $next;
    }

    public function hasPrevious(): bool
    {
        return $this->getPrevious() !== '';
    }

    public function hasNext(): bool
    {
        return $this->getNext() !== '';
    }
}
