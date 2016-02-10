<?php

namespace BabDev\Website\Service;

use BabDev\Website\Application;
use BabDev\Website\Renderer\TwigExtension;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Renderer\RendererInterface;
use Joomla\Renderer\TwigRenderer;

/**
 * Twig renderer service provider.
 */
class TwigRendererProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $container->alias('renderer', RendererInterface::class)
            ->alias(TwigRenderer::class, RendererInterface::class)
            ->set(
                RendererInterface::class,
                function (Container $container): RendererInterface {
                    /* @type  \Joomla\Registry\Registry $config */
                    $config = $container->get('config');

                    // Instantiate the renderer object
                    $rendererConfig = (array) $config->get('template');

                    // If the cache isn't false, then it should be a file path relative to the app root
                    $rendererConfig['cache'] = $rendererConfig['cache'] === false ? false : JPATH_ROOT . '/cache/twig';

                    // Instantiate the renderer object
                    $renderer = new TwigRenderer($rendererConfig);

                    // Add our Twig extension
                    $renderer->getRenderer()->addExtension(new TwigExtension($container->get(Application::class)));

                    // Add Twig's Text extension
                    $renderer->getRenderer()->addExtension(new \Twig_Extensions_Extension_Text());

                    // Add the debug extension if enabled
                    if ($config->get('template.debug')) {
                        $renderer->getRenderer()->addExtension(new \Twig_Extension_Debug);
                    }

                    // Set the Lexer object
                    $renderer->getRenderer()->setLexer(
                        new \Twig_Lexer(
                            $renderer->getRenderer(),
                            [
                                'delimiters' => [
                                    'tag_comment'  => ['{#', '#}'],
                                    'tag_block'    => ['{%', '%}'],
                                    'tag_variable' => ['{{', '}}'],
                                ],
                            ]
                        )
                    );

                    return $renderer;
                },
                true,
                true
            );
    }
}
