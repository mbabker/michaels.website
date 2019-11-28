<?php declare(strict_types=1);

namespace BabDev\Website\Controller;

use Joomla\Application\WebApplication;
use Joomla\Input\Input;
use Joomla\Renderer\RendererInterface;
use Joomla\Router\Exception\RouteNotFoundException;
use Zend\Diactoros\Response\HtmlResponse;

final class PageController extends AbstractController
{
    /**
     * Container defining layouts which shouldn't be routable.
     *
     * @var array
     */
    private const EXCLUDED_LAYOUTS = ['base', 'exception', 'homepage'];

    private RendererInterface $renderer;

    public function __construct(RendererInterface $renderer, WebApplication $app, Input $input = null)
    {
        parent::__construct($app, $input);

        $this->renderer = $renderer;
    }

    public function execute(): bool
    {
        $view   = strtolower($this->getInput()->getString('view', ''));
        $layout = "$view.html.twig";

        // Since this is a catch-all route, if the layout doesn't exist, or is an excluded layout, treat this as a 404
        if (!$this->renderer->pathExists($layout) || \in_array($view, self::EXCLUDED_LAYOUTS)) {
            throw new RouteNotFoundException(
                sprintf('Unable to handle request for route `%s`.', $this->getApplication()->get('uri.route')),
                404
            );
        }

        $this->getApplication()->setResponse(new HtmlResponse($this->renderer->render($layout)));

        return true;
    }
}
