<?php

namespace BabDev\Website;

use Joomla\Application\AbstractWebApplication;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Router\Router;

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
            // Fetch and execute the controller
            $this->router->getController($this->get('uri.route'))->execute();
        } catch (\Throwable $throwable) {
            $this->setErrorHeader($throwable);
            $this->setErrorOutput($throwable);
        }
    }

    private function setErrorHeader(\Throwable $throwable)
    {
        switch ($throwable->getCode()) {
            case 404:
                $this->setHeader('HTTP/1.1 404 Not Found', 404, true);

                break;

            case 500:
            default:
                $this->setHeader('HTTP/1.1 500 Internal Server Error', 500, true);

                break;
        }
    }

    private function setErrorOutput(\Throwable $throwable)
    {
        $this->setBody(
            $this->getContainer()->get('renderer')->render('exception.html.twig', ['exception' => $throwable])
        );
    }

    public function setRouter(Router $router): self
    {
        $this->router = $router;

        return $this;
    }
}
