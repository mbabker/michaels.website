<?php

namespace App\Sheets;

use Carbon\Carbon;
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
class BlogPost extends Sheet
{
    public function getPublishedDateAttribute(string $value): Carbon
    {
        return Carbon::parse($value);
    }

    public function getModifiedDateAttribute(string $value): Carbon
    {
        return Carbon::parse($value);
    }

    public function getUrlAttribute(): string
    {
        return route('blog.show', ['slug' => $this->slug]);
    }
}
