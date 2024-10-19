<?php

namespace App\Sheets;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\HtmlString;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Sheet;

/**
 * @property string      $guid
 * @property string      $slug
 * @property string      $author
 * @property Carbon      $published_date
 * @property Carbon      $modified_date
 * @property string      $title
 * @property string|null $image
 * @property string|null $image_credit
 * @property string      $teaser
 * @property HtmlString  $contents
 *
 * @property-read string $url
 */
final class BlogPost extends Sheet implements Feedable
{
    /**
     * @return Collection<array-key, self>
     */
    public static function getFeedItems(): Collection
    {
        return Sheets::collection('blog')->all();
    }

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->guid)
            ->title($this->title)
            ->summary($this->teaser)
            ->updated($this->modified_date)
            ->link($this->url)
            ->authorName($this->author);
    }

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
