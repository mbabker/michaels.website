<?php

namespace BabDev\Website\Renderer;

use Symfony\Component\Asset\Packages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class TwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset', [Packages::class, 'getUrl']),
            new TwigFunction('first_paragraph', [$this, 'getFirstParagraph']),
            new TwigFunction('preload', [TwigRuntime::class, 'preloadAsset']),
            new TwigFunction('request_uri', [TwigRuntime::class, 'getRequestUri']),
            new TwigFunction('route', [TwigRuntime::class, 'getRouteUri']),
            new TwigFunction('render_pagination', [TwigRuntime::class, 'renderPagination'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('get_class', 'get_class'),
            new TwigFilter('strip_root_path', [$this, 'stripRootPath']),
        ];
    }

    public function getFirstParagraph(string $text): string
    {
        preg_match("/<p>(.*)<\/p>/", $text, $matches);

        return strip_tags(html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8'));
    }

    public function stripRootPath(string $string): string
    {
        return str_replace(JPATH_ROOT, '', $string);
    }
}
