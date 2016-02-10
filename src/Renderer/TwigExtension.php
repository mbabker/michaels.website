<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Renderer;

use BabDev\Website\Application;
use BabDev\Website\Entity\User;
use BabDev\Website\Factory;

/**
 * Twig extension class
 *
 * @since  1.0
 */
class TwigExtension extends \Twig_Extension
{
	/**
	 * Application object
	 *
	 * @var    Application
	 * @since  1.0
	 */
	private $app;

	/**
	 * Constructor
	 *
	 * @param   Application  $app  The application object
	 *
	 * @since   1.0
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Returns the name of the extension
	 *
	 * @return  string  The extension name
	 *
	 * @since   1.0
	 */
	public function getName()
	{
		return 'babdev-michaels-website';
	}

	/**
	 * Returns a list of functions to add to the existing list
	 *
	 * @return  \Twig_SimpleFunction[]  An array of functions
	 *
	 * @since   1.0
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('sprintf', 'sprintf'),
			new \Twig_SimpleFunction('stripJRoot', [$this, 'stripJRoot']),
			new \Twig_SimpleFunction('getFirstParagraph', [$this, 'getFirstParagraph']),
			new \Twig_SimpleFunction('asset', [$this, 'getAssetUri']),
			new \Twig_SimpleFunction('route', [$this, 'getRouteUri']),
			new \Twig_SimpleFunction('currentRoute', [$this, 'isCurrentRoute']),
			new \Twig_SimpleFunction('requestURI', [$this, 'getRequestUri']),
		];
	}

	/**
	 * Returns a list of filters to add to the existing list
	 *
	 * @return  \Twig_SimpleFilter[]  An array of filters
	 *
	 * @since   1.0
	 */
	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('basename', 'basename'),
			new \Twig_SimpleFilter('get_class', 'get_class'),
			new \Twig_SimpleFilter('json_decode', 'json_decode'),
			new \Twig_SimpleFilter('stripJRoot', [$this, 'stripJRoot'])
		];
	}

	/**
	 * Retrieves the URI for a web asset
	 *
	 * @param   string  $asset  The asset to process
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getAssetUri($asset)
	{
		return $this->app->get('uri.media.full') . $asset;
	}

	/**
	 * Retrieves the first paragraph of text for an article
	 *
	 * @param   string  $text  Article text to search
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getFirstParagraph($text)
	{
		preg_match("/<p>(.*)<\/p>/", $text, $matches);

		return strip_tags(html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8'));
	}

	/**
	 * Retrieves the current URI
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getRequestUri()
	{
		return $this->app->get('uri.request');
	}

	/**
	 * Retrieves the URI for a route
	 *
	 * @param   string  $route  The route to process
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getRouteUri($route)
	{
		return $this->app->get('uri.base.full') . $route;
	}

	/**
	 * Check if a route is the route for the current page
	 *
	 * @param   string  $route  The route to process
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function isCurrentRoute($route)
	{
		return $this->app->get('uri.route') === $route;
	}

	/**
	 * Replaces the application root path defined by the constant "JPATH_ROOT" with the string "APP_ROOT"
	 *
	 * @param   string  $string  The string to process
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function stripJRoot($string)
	{
		return str_replace(JPATH_ROOT, 'APP_ROOT', $string);
	}
}
