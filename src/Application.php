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
     * {@inheritdoc}
     */
    protected function initialise()
    {
        // Set the MIME for the application based on format
        switch (strtolower($this->input->getWord('format', 'html'))) {
            case 'json':
                $this->mimeType = 'application/json';

                break;

            // Don't need to do anything for the default case
            default:
                break;
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
        switch (strtolower($this->input->getWord('format', 'html'))) {
            case 'json':
                $data = [
                    'code'    => $throwable->getCode(),
                    'message' => $throwable->getMessage(),
                    'error'   => true,
                ];

                $body = json_encode($data);

                break;

            case 'html':
            default:
                $body = $this->getContainer()->get('renderer')->render('exception.html.twig', ['exception' => $throwable]);

                break;
        }

        $this->setBody($body);
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
