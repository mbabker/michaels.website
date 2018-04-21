<?php

namespace BabDev\Website\Renderer;

use Symfony\Component\Asset\Packages;

final class TwigExtension extends \Twig_Extension
{
    public function getFunctions(): array
    {
        return [
            new \Twig_Function('asset', [Packages::class, 'getUrl']),
            new \Twig_Function('first_paragraph', [$this, 'getFirstParagraph']),
            new \Twig_Function('preload', [TwigRuntime::class, 'preloadAsset']),
            new \Twig_Function('request_uri', [TwigRuntime::class, 'getRequestUri']),
            new \Twig_Function('route', [TwigRuntime::class, 'getRouteUri']),
            new \Twig_Function('render_pagination', [TwigRuntime::class, 'renderPagination'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters(): array
    {
        return [
            new \Twig_Filter('get_class', 'get_class'),
            new \Twig_Filter('strip_root_path', [$this, 'stripRootPath']),
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
