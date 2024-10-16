<?php

namespace App\Console\Commands;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'sitemap:generate', description: 'Generate the sitemap.')]
final class GenerateSitemap extends Command
{
    public function handle(): void
    {
        $this->components->info('Generating sitemap...');

        SitemapGenerator::create(config()->string('app.url'))
            ->shouldCrawl(static fn (Uri $uri): bool => $uri->getPath() !== '')
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

        $this->components->info('Sitemap generated!');
    }
}
