<?php

namespace BabDev\Website\Renderer;

/**
 * Twig extension class.
 */
class TwigExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_Function('asset', [TwigRuntime::class, 'getAssetUri']),
            new \Twig_Function('first_paragraph', [$this, 'getFirstParagraph']),
            new \Twig_Function('request_uri', [TwigRuntime::class, 'getRequestUri']),
            new \Twig_Function('route', [TwigRuntime::class, 'getRouteUri']),
            new \Twig_Function('render_pagination', [TwigRuntime::class, 'renderPagination'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new \Twig_Filter('get_class', 'get_class'),
            new \Twig_Filter('strip_root_path', [$this, 'stripRootPath']),
        ];
    }

    /**
     * Retrieves the first paragraph of text for an article.
     *
     * @param string $text Article text to search
     *
     * @return string
     */
    public function getFirstParagraph(string $text): string
    {
        preg_match("/<p>(.*)<\/p>/", $text, $matches);

        return strip_tags(html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8'));
    }

    /**
     * Strips the application root path from a string.
     *
     * @param string $string The string to process
     *
     * @return string
     */
    public function stripRootPath(string $string)
    {
        return str_replace(JPATH_ROOT, '', $string);
    }
}
