<?php

namespace BabDev\Website\Renderer;

use BabDev\Website\Model\BlogPostModel;
use Joomla\Filesystem\Folder;
use Symfony\Component\Yaml\Parser;

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
            new \Twig_Function('sprintf', 'sprintf'),
            new \Twig_Function('stripJRoot', [$this, 'stripJRoot']),
            new \Twig_Function('getFirstParagraph', [$this, 'getFirstParagraph']),
            new \Twig_Function('asset', [TwigRuntime::class, 'getAssetUri']),
            new \Twig_Function('route', [TwigRuntime::class, 'getRouteUri']),
            new \Twig_Function('currentRoute', [TwigRuntime::class, 'isCurrentRoute']),
            new \Twig_Function('requestURI', [TwigRuntime::class, 'getRequestUri']),
            new \Twig_Function('getPage', [$this, 'getPage']),
            new \Twig_Function('get_all_blog_posts', [BlogPostModel::class, 'getPosts']),
            new \Twig_Function('render_pagination', [TwigRuntime::class, 'renderPagination'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new \Twig_Filter('basename', 'basename'),
            new \Twig_Filter('get_class', 'get_class'),
            new \Twig_Filter('json_decode', 'json_decode'),
            new \Twig_Filter('stripJRoot', [$this, 'stripJRoot']),
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
     * Get the requested page.
     *
     * @param string $section The section to lookup
     * @param string $page    The page to lookup in the section
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function getPage(string $section, string $page): array
    {
        $lookupPath = JPATH_ROOT . '/pages/' . $section;

        $files = Folder::files($lookupPath, '.yml');

        foreach ($files as $file) {
            $parts = explode('_', $file);

            if ($parts[1] === $page . '.yml') {
                return (new Parser)->parse(file_get_contents($lookupPath . '/' . $file));
            }
        }

        throw new \InvalidArgumentException(sprintf('Unable to handle request for route `%s`.', $this->app->get('uri.route')), 404);
    }

    /**
     * Get all pages in a section.
     *
     * @param string $section The section to lookup
     *
     * @return array
     */
    public function getPages(string $section): array
    {
        $lookupPath = JPATH_ROOT . '/pages/' . $section;

        $files = Folder::files($lookupPath, '.yml');
        $pages = [];

        foreach ($files as $file) {
            $parts            = explode('_', $file);
            $pages[$parts[0]] = (new Parser)->parse(file_get_contents($lookupPath . '/' . $file));
        }

        return $pages;
    }

    /**
     * Replaces the application root path defined by the constant "JPATH_ROOT" with the string "APP_ROOT".
     *
     * @param string $string The string to process
     *
     * @return string
     */
    public function stripJRoot(string $string)
    {
        return str_replace(JPATH_ROOT, 'APP_ROOT', $string);
    }
}
