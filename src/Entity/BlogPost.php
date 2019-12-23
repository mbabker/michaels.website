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

    public function hasPrevious(): bool
    {
        return $this->previous !== null;
    }

    public function hasNext(): bool
    {
        return $this->next !== null;
    }
}
