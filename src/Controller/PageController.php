<?php

namespace BabDev\Website\Controller;

use BabDev\Website\Application;
use Joomla\Controller\AbstractController;
use Joomla\Renderer\RendererInterface;

/**
 * Controller rendering single pages.
 *
 * @method         Application  getApplication()  Get the application object.
 * @property-read  Application $app              Application object
 */
class PageController extends AbstractController
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
        $layout = $this->getInput()->getString('view', '') . '.html.twig';

        // Since this is a catch-all route, if the layout doesn't exist, treat this as a 404
        if (!$this->renderer->pathExists($layout)) {
            throw new \RuntimeException(
                sprintf('Unable to handle request for route `%s`.', $this->getApplication()->get('uri.route')), 404
            );
        }

        $this->getApplication()->setBody($this->renderer->render($layout));

        return true;
    }
}
