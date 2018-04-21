<?php

namespace BabDev\Website\EventListener;

use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationErrorEvent;
use Joomla\Application\WebApplication;
use Joomla\Event\SubscriberInterface;
use Joomla\Renderer\RendererInterface;
use Zend\Diactoros\Response\HtmlResponse;

final class ErrorSubscriber implements SubscriberInterface
{
    /**
     * @var RendererInterface
     */
    private $renderer;

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApplicationEvents::ERROR => 'handleError',
        ];
    }

    public function handleError(ApplicationErrorEvent $event)
    {
        /** @var WebApplication $app */
        $app = $event->getApplication();

        $response = new HtmlResponse(
            $this->renderer->render('exception.html.twig', ['exception' => $event->getError()])
        );

        switch ($event->getError()->getCode()) {
            case 404:
                $response = $response->withStatus(404);
                break;

            case 500:
            default:
                $response = $response->withStatus(500);
                break;
        }

        $app->setResponse($response);
    }
}
