<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Renderer;

use BabDev\Website\Application;
use BabDev\Website\Factory;

/**
 * Twig extension class
 *
 * @since  1.0
 */
class TwigExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
	/**
	 * Application object
	 *
	 * @var    Application
	 * @since  1.0
	 */
	private $app;

	/**
	 * Container for the admin breadcrumbs
	 *
	 * @var    array
	 * @since  1.0
	 */
	private $breadcrumbLookup = [
		'article'    => '<i class="fa fa-newspaper-o"></i> Article',
		'articles'   => '<i class="fa fa-book"></i> Article Manager',
		'categories' => '<i class="fa fa-list"></i> Category Manager',
		'category'   => '<i class="fa fa-ellipsis-h"></i> Category',
		'users'      => '<i class="fa fa-users"></i> User Manager',
		'user'       => '<i class="fa fa-user"></i> User'
	];

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
	 * Returns a list of global variables to add to the existing list
	 *
	 * @return  array  An array of global variables
	 *
	 * @since   1.0
	 */
	public function getGlobals()
	{
		$twigGlobals = [
			'uri'               => $this->app->get('uri'),
			'userAuthenticated' => $this->app->getUser()->isAuthenticated(),
			'currentUser'       => $this->app->getUser(),
			'messages'          => $this->app->getMessageQueue(),
			'now'               => new \DateTime('now', new \DateTimeZone('UTC'))
		];

		$this->app->clearMessageQueue();

		return $twigGlobals;
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
			new \Twig_SimpleFunction('gravatar', [$this, 'getGravatar']),
			new \Twig_SimpleFunction('getBreadcrumb', [$this, 'getBreadcrumb']),
			new \Twig_SimpleFunction('getCategoryList', [$this, 'getCategoryList']),
			new \Twig_SimpleFunction('getFirstParagraph', [$this, 'getFirstParagraph'])
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
	 * Retrieves the breadcrumb item for a given key
	 *
	 * @param   string  $key  The string to process
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getBreadcrumb($key)
	{
		return $this->breadcrumbLookup[$key];
	}

	/**
	 * Retrieves the list of categories for a given extension
	 *
	 * @param   string  $extension  The extension to process
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getCategoryList($extension)
	{
		/** @var \BabDev\Website\Entity\CategoryRepository $repo */
		$repo = Factory::get('repository', '\\BabDev\\Website\\Entity\\Category');

		return $repo->getCategoryList($extension);
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
	 * Get the specified user's gravatar
	 *
	 * @param   string   $email  E-mail address to lookup
	 * @param   integer  $size   Size in pixels of the image
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getGravatar($email, $size = 50)
	{
		return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '&s=' . $size;
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
