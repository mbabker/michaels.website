<?php

namespace BabDev\Website\Renderer;

use BabDev\Website\Application;
use Joomla\Filesystem\Folder;
use Symfony\Component\Yaml\Parser;

/**
 * Twig extension class.
 */
class TwigExtension extends \Twig_Extension
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app The application object
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'babdev-michaels-website';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('sprintf', 'sprintf'),
            new \Twig_SimpleFunction('stripJRoot', [$this, 'stripJRoot']),
            new \Twig_SimpleFunction('getFirstParagraph', [$this, 'getFirstParagraph']),
            new \Twig_SimpleFunction('asset', [$this, 'getAssetUri']),
            new \Twig_SimpleFunction('route', [$this, 'getRouteUri']),
            new \Twig_SimpleFunction('currentRoute', [$this, 'isCurrentRoute']),
            new \Twig_SimpleFunction('requestURI', [$this, 'getRequestUri']),
            new \Twig_SimpleFunction('getPage', [$this, 'getPage']),
            new \Twig_SimpleFunction('getPages', [$this, 'getPages']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('basename', 'basename'),
            new \Twig_SimpleFilter('get_class', 'get_class'),
            new \Twig_SimpleFilter('json_decode', 'json_decode'),
            new \Twig_SimpleFilter('stripJRoot', [$this, 'stripJRoot']),
        ];
    }

    /**
     * Retrieves the URI for a web asset.
     *
     * @param string $asset The asset to process
     *
     * @return string
     */
    public function getAssetUri(string $asset): string
    {
        return $this->app->get('uri.media.full') . $asset;
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
     * Retrieves the current URI.
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        return $this->app->get('uri.request');
    }

    /**
     * Retrieves the URI for a route.
     *
     * @param string $route The route to process
     *
     * @return string
     */
    public function getRouteUri(string $route): string
    {
        return $this->app->get('uri.base.full') . $route;
    }

    /**
     * Check if a route is the route for the current page.
     *
     * @param string $route The route to process
     *
     * @return bool
     */
    public function isCurrentRoute(string $route): string
    {
        return $this->app->get('uri.route') === $route;
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
