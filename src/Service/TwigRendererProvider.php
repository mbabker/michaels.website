<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Service;

use BabDev\Website\Application;
use BabDev\Website\Renderer\TwigExtension;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Renderer\TwigRenderer;

/**
 * Twig renderer service provider
 *
 * @since  1.0
 */
class TwigRendererProvider implements ServiceProviderInterface
{
	/**
	 * Application object
	 *
	 * @var    Application
	 * @since  1.0
	 */
	private $app;

	/**
	 * Constructor.
	 *
	 * @param   Application  $app  Application object
	 *
	 * @since   1.0
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
	}

	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function register(Container $container)
	{
		$container->set('Joomla\\Renderer\\RendererInterface',
			function (Container $container) {
				/* @type  \Joomla\Registry\Registry  $config */
				$config = $container->get('config');

				// Instantiate the renderer object
				$renderer = new TwigRenderer($config->get('template'));

				// Add our Twig extension
				$renderer->getRenderer()->addExtension(new TwigExtension($this->app));

				// Add the debug extension if enabled
				if ($config->get('template.debug'))
				{
					$renderer->getRenderer()->addExtension(new \Twig_Extension_Debug);
				}

				// Set the Lexer object
				$renderer->getRenderer()->setLexer(
					new \Twig_Lexer($renderer->getRenderer(), ['delimiters' => [
						'tag_comment'  => ['{#', '#}'],
						'tag_block'    => ['{%', '%}'],
						'tag_variable' => ['{{', '}}']
					]])
				);

				return $renderer;
			},
			true,
			true
		);

		// Alias the renderer
		$container->alias('renderer', 'Joomla\\Renderer\\RendererInterface');
	}
}
