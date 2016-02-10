<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Controller;

use BabDev\Website\Application;
use Joomla\Controller\AbstractController;
use Joomla\Renderer\RendererInterface;

/**
 * Controller rendering layout files for the application
 *
 * @method         Application  getApplication()  Get the application object.
 * @property-read  Application  $app              Application object
 *
 * @since          1.0
 */
class RenderController extends AbstractController
{
	/**
	 * The template renderer
	 *
	 * @var    RendererInterface
	 * @since  1.0
	 */
	private $renderer;

	/**
	 * Instantiate the controller.
	 *
	 * @param   RendererInterface  $renderer  The template renderer.
	 *
	 * @since   1.0
	 */
	public function __construct(RendererInterface $renderer)
	{
		$this->renderer = $renderer;
	}

	/**
	 * Execute the controller
	 *
	 * @return  boolean  True if controller finished execution
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 * @throws  \RuntimeException
	 */
	public function execute()
	{
		// Check the slug and match it to a layout file (with special cases)
		$layout = $this->getInput()->getPath('slug', '') . '.html.twig';
		$item   = '';

		// If empty, assume we're on the homepage
		if ($layout === '.html.twig')
		{
			$layout = 'homepage.html.twig';
		}

		$route = $this->getApplication()->get('uri.route');
		$parts = explode('/', $route);

		// If there are multiple segments, run extra checks
		if (count($parts) > 1)
		{
			switch ($parts[0])
			{
				case 'blog':
					$layout = 'blog/layout.html.twig';
					$item   = $parts[1];
			}
		}

		// Check if layout exists
		if (!$this->renderer->pathExists($layout))
		{
			throw new \InvalidArgumentException(sprintf('Unable to handle request for route `%s`.', $route), 404);
		}

		$this->getApplication()->setBody($this->renderer->render($layout));

		return true;
	}
}
