<?php

namespace BabDev\Website;

use DebugBar\DebugBar;
use Joomla\Renderer\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\EmitterInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Stream;

final class ExceptionHandler
{
    /**
     * @var DebugBar
     */
    private $debugBar;

    /**
     * @var EmitterInterface
     */
    private $emitter;

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(RendererInterface $renderer, ?DebugBar $debugBar)
    {
        $this->debugBar = $debugBar;
        $this->renderer = $renderer;

        $this->emitter = new SapiEmitter();
    }

    public function handle(\Throwable $throwable): void
    {
        $response = new HtmlResponse(
            $this->renderer->render('exception.html.twig', ['exception' => $throwable])
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

        if ($this->debugBar) {
            /** @var \DebugBar\DataCollector\ExceptionsCollector $collector */
            $collector = $this->debugBar->getCollector('exceptions');
            $collector->addThrowable($throwable);

            $debugBarOutput = $this->debugBar->getJavascriptRenderer()->render();

            // Fetch the body
            $body = (string) $response->getBody();

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
            $stream = new Stream('php://memory', 'rw');
            $stream->write((string) $body);
            $response = $response->withBody($stream);
        }

        $this->emitter->emit($response);
    }
}
