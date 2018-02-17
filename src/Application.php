<?php

namespace BabDev\Website;

use DebugBar\DebugBar;
use Joomla\Application\AbstractWebApplication;
use Joomla\Controller\ControllerInterface;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Renderer\RendererInterface;
use Joomla\Router\Router;
use Zend\Diactoros\Response\HtmlResponse;

final class Application extends AbstractWebApplication implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var Router
     */
    private $router;

    /**
     * {@inheritdoc}
     */
    protected function doExecute(): void
    {
        try {
            $route = $this->router->parseRoute($this->get('uri.route'), $this->input->getMethod());

            // Add variables to the input if not already set
            foreach ($route['vars'] as $key => $value) {
                $this->input->def($key, $value);
            }

            /** @var ControllerInterface $controller */
            $controller = $this->getContainer()->get($route['controller']);
            $controller->execute();
        } catch (\Throwable $throwable) {
            $this->allowCache(false);

            if ($this->getContainer()->has(DebugBar::class)) {
                $this->getContainer()->get(DebugBar::class)->getCollector('exceptions')->addThrowable($throwable);
            }

            $response = new HtmlResponse(
                $this->getContainer()->get(RendererInterface::class)->render('exception.html.twig', ['exception' => $throwable])
            );

            switch ($throwable->getCode()) {
                case 404:
                    $response = $response->withStatus(404);
                    break;

                case 500:
                default:
                    $response = $response->withStatus(500);
                    break;
            }

            $this->setResponse($response);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFormToken($forceNew = false): string
    {
        return '';
    }

    public function setRouter(Router $router): self
    {
        $this->router = $router;

        return $this;
    }
}
