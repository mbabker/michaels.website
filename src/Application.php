<?php

namespace BabDev\Website;

use DebugBar\DebugBar;
use Joomla\Application\AbstractWebApplication;
use Joomla\Controller\ControllerInterface;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Router\Router;
use Zend\Diactoros\Response\HtmlResponse;

final class Application extends AbstractWebApplication implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var DebugBar
     */
    private $debugBar;

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

            if ($this->debugBar) {
                $this->debugBar->getCollector('exceptions')->addThrowable($throwable);
            }

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

    /**
     * {@inheritdoc}
     */
    protected function respond(): void
    {
        // Render the debug bar output if able
        if ($this->debugBar && !($this->mimeType === 'application/json' || $this->getResponse() instanceof JsonResponse)) {
            $debugBarOutput = $this->debugBar->getJavascriptRenderer()->render();

            // Fetch the body
            $body = $this->getBody();

            // If for whatever reason we're missing the closing body tag, just append the scripts
            if (!stristr($body, '</body>')) {
                $body .= $debugBarOutput;
            } else {
                // Find the closing tag and put the scripts in
                $pos = strripos($body, '</body>');

                if ($pos !== false) {
                    $body = substr_replace($body, $debugBarOutput . '</body>', $pos, strlen('</body>'));
                }
            }

            // Reset the body
            $this->setBody($body);
        }

        parent::respond();
    }

    public function setDebugBar(DebugBar $debugBar): self
    {
        $this->debugBar = $debugBar;

        return $this;
    }

    public function setRouter(Router $router): self
    {
        $this->router = $router;

        return $this;
    }
}
