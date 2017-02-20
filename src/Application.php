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

    /**
     * Set the HTTP Response Header for error conditions.
     *
     * @param \Throwable $throwable The Throwable object
     */
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

    /**
     * Set the body for error conditions.
     *
     * @param \Throwable $throwable The Throwable object
     */
    private function setErrorOutput(\Throwable $throwable)
    {
        $this->setBody(
            $this->getContainer()->get('renderer')->render('exception.html.twig', ['exception' => $throwable])
        );
    }

    /**
     * Set the application's router.
     *
     * @param Router $router Router object to set.
     *
     * @return $this
     */
    public function setRouter(Router $router): Application
    {
        $this->router = $router;

        return $this;
    }
}
