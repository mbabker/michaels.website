<?php

namespace App\Console\Commands;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
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
            ->shouldCrawl(static function (Uri $uri): bool {
                // Don't include the homepage without a trailing slash
                if ($uri->getPath() === '') {
                    return false;
                }

                return true;
            })
            ->hasCrawled(static function (Url $url): Url {
                if ($url->path() === '/') {
                    $url->setPriority(1.0);
                }

                if (str_starts_with($url->path(), '/blog/')) {
                    $url->setPriority(0.5);
                }

                return $url;
            })
            ->getSitemap()
            ->writeToDisk('local', 'sitemap.xml');

        $this->info('Sitemap generated!');
    }
}
