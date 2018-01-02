<?php

namespace BabDev\Website\Controller;

use BabDev\Website\Application;
use Joomla\Controller\AbstractController;
use Joomla\Renderer\RendererInterface;
use Joomla\Router\Exception\RouteNotFoundException;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Controller rendering single pages.
 *
 * @method        Application getApplication() Get the application object.
 * @property-read Application $app             Application object
 */
final class PageController extends AbstractController
{
    /**
     * Container defining layouts which shouldn't be routable.
     *
     * @var array
     */
    private $excludedLayouts = ['base', 'exception', 'homepage'];

    /**
     * @var RendererInterface
     */
    private $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function execute(): bool
    {
        $view   = $this->getInput()->getString('view', '');
        $layout = "$view.html.twig";

        // Since this is a catch-all route, if the layout doesn't exist, or is an excluded layout, treat this as a 404
        if (!$this->renderer->pathExists($layout) || in_array($view, $this->excludedLayouts)) {
            throw new RouteNotFoundException(
                sprintf('Unable to handle request for route `%s`.', $this->getApplication()->get('uri.route')),
                404
            );
        }

        $this->getApplication()->setResponse(new HtmlResponse($this->renderer->render($layout)));

        return true;
    }
}
