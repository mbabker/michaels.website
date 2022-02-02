<?php

namespace App\Sheets;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\HtmlString;
use Spatie\Sheets\Sheet;

/**
 * @property string      $slug
 * @property string      $author
 * @property Carbon      $published_date
 * @property Carbon      $modified_date
 * @property string      $title
 * @property string|null $image
 * @property string      $teaser
 * @property HtmlString  $contents
 * @property string      $url
 */
final class BlogPost extends Sheet
{
    public function getPublishedDateAttribute(string $value): Carbon
    {
        return Date::parse($value);
    }

    public function getModifiedDateAttribute(string $value): Carbon
    {
        return Date::parse($value);
    }

    public function getUrlAttribute(): string
    {
        return route('blog.show', ['slug' => $this->slug]);
    }
}
