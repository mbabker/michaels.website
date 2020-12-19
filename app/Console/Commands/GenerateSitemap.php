<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

final class GenerateSitemap extends Command
{
    /**
     * @var string
     */
    protected $name = 'sitemap:generate';

    /**
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    public function handle(): void
    {
        $this->info('Generating sitemap...');

        SitemapGenerator::create(config('app.url'))
            ->hasCrawled(static function (Url $url): ?Url {
                // Don't include the homepage without a trailing slash
                if ($url->path() === '') {
                    return null;
                }

                return $url;
            })
            ->getSitemap()
            ->writeToDisk('local', 'sitemap.xml');

        $this->info('Sitemap generated!');
    }
}
