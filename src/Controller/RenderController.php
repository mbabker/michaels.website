<?php

namespace BabDev\Website\Controller;

use BabDev\Website\Application;
use Joomla\Controller\AbstractController;
use Joomla\Renderer\RendererInterface;

/**
 * Controller rendering layout files for the application.
 *
 * @method         Application  getApplication()  Get the application object.
 * @property-read  Application  $app              Application object
 */
class RenderController extends AbstractController
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @param RendererInterface $renderer The template renderer.
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function execute(): bool
    {
        // Check the slug and match it to a layout file (with special cases)
        $layout = $this->getInput()->getPath('slug', '') . '.html.twig';
        $item   = '';

        // If empty, assume we're on the homepage
        if ($layout === '.html.twig') {
            $layout = 'homepage.html.twig';
        }

        $route = $this->getApplication()->get('uri.route');
        $parts = explode('/', $route);

        // If there are multiple segments, run extra checks
        if (count($parts) > 1) {
            switch ($parts[0]) {
                case 'blog':
                    $layout = 'blog/layout.html.twig';
                    $item   = $parts[1];
            }
        }

        // Check if layout exists
        if (!$this->renderer->pathExists($layout)) {
            throw new \InvalidArgumentException(sprintf('Unable to handle request for route `%s`.', $route), 404);
        }

        $this->getApplication()->setBody($this->renderer->render($layout, ['item' => $item]));

        return true;
    }
}
