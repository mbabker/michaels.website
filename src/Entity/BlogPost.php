<?php declare(strict_types=1);

namespace BabDev\Website\Entity;

final class BlogPost
{
    public string $author = '';

    public string $category = '';

    public ?\DateTimeInterface $publish_up = null;

    public ?\DateTimeInterface $date_modified = null;

    public string $title = '';

    public string $alias = '';

    public string $text = '';

    public string $preview = '';

    public ?string $image = null;

    public ?string $previous = null;

    public ?string $next = null;

    /**
     * @note This setter is necessary to have the entity be properly deserialized
     */
    public function setPublishUp(?\DateTimeInterface $publishUp): void
    {
        $this->publish_up = $publishUp;
    }

    /**
     * @note This setter is necessary to have the entity be properly deserialized
     */
    public function setDateModified(?\DateTimeInterface $dateModified): void
    {
        $this->date_modified = $dateModified;
    }

    public function hasPrevious(): bool
    {
        return $this->previous !== null;
    }

    public function hasNext(): bool
    {
        return $this->next !== null;
    }
}
