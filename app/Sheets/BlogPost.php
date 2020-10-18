<?php

namespace App\Sheets;

use Carbon\Carbon;
use Illuminate\Support\HtmlString;
use Spatie\Sheets\Sheet;

/**
 * @property string      $slug
 * @property string      $author
 * @property string      $publish_up
 * @property string      $date_modified
 * @property string      $title
 * @property string|null $image
 * @property string      $teaser
 * @property HtmlString  $contents
 * @property Carbon      $published_date
 * @property Carbon      $modified_date
 * @property string      $url
 */
class BlogPost extends Sheet
{
    public function getPublishedDateAttribute(): Carbon
    {
        return Carbon::parse($this->publish_up);
    }

    public function getModifiedDateAttribute(): Carbon
    {
        return Carbon::parse($this->date_modified);
    }

    public function getUrlAttribute(): string
    {
        return route('blog.show', ['slug' => $this->slug]);
    }
}
