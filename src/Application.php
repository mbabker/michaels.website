<?php

namespace BabDev\Website;

use Joomla\Application\AbstractWebApplication;
use Joomla\Controller\ControllerInterface;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Router\Router;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Web application class.
 */
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
    protected function doExecute()
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

            $response = new HtmlResponse(
                $this->getContainer()->get('renderer')->render('exception.html.twig', ['exception' => $throwable])
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
