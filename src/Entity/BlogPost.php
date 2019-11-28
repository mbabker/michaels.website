<?php declare(strict_types=1);

namespace BabDev\Website\Entity;

final class BlogPost
{
    /**
     * @var string
     */
    private string $author = '';

    /**
     * @var string
     */
    private string $category = '';

    /**
     * @var \DateTimeInterface|null
     */
    private ?\DateTimeInterface $publish_up;

    /**
     * @var \DateTimeInterface|null
     */
    private ?\DateTimeInterface $date_modified;

    /**
     * @var string
     */
    private string $title = '';

    /**
     * @var string
     */
    private string $alias = '';

    /**
     * @var string
     */
    private string $text = '';

    /**
     * @var string
     */
    private string $preview = '';

    /**
     * @var string|null
     */
    private ?string $image;

    /**
     * @var string|null
     */
    private ?string $previous;

    /**
     * @var string|null
     */
    private ?string $next;

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getPublishUp(): ?\DateTimeInterface
    {
        return $this->publish_up;
    }

    public function setPublishUp(?\DateTimeInterface $publish_up): void
    {
        $this->publish_up = $publish_up;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->date_modified;
    }

    public function setDateModified(?\DateTimeInterface $date_modified): void
    {
        $this->date_modified = $date_modified;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getPreview(): string
    {
        return $this->preview;
    }

    public function setPreview(string $preview): void
    {
        $this->preview = $preview;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getPrevious(): ?string
    {
        return $this->previous;
    }

    public function setPrevious(?string $previous): void
    {
        $this->previous = $previous;
    }

    public function getNext(): ?string
    {
        return $this->next;
    }

    public function setNext(?string $next): void
    {
        $this->next = $next;
    }

    public function hasPrevious(): bool
    {
        return $this->getPrevious() !== null;
    }

    public function hasNext(): bool
    {
        return $this->getNext() !== null;
    }
}
