<?php

namespace BabDev\Website\EventListener;

use BabDev\Website\Application;
use DebugBar\DebugBar;
use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationErrorEvent;
use Joomla\Application\Event\ApplicationEvent;
use Joomla\Event\Priority;
use Joomla\Event\SubscriberInterface;
use Zend\Diactoros\Stream;

final class DebugSubscriber implements SubscriberInterface
{
    /**
     * @var DebugBar
     */
    private $debugBar;

    public function __construct(DebugBar $debugBar)
    {
        $this->debugBar = $debugBar;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApplicationEvents::BEFORE_EXECUTE => ['markBeforeExecute', Priority::HIGH],
            ApplicationEvents::AFTER_EXECUTE  => ['markAfterExecute', Priority::LOW],
            ApplicationEvents::BEFORE_RESPOND => 'handleDebugResponse',
            ApplicationEvents::ERROR          => 'handleError',
        ];
    }

    public function handleDebugResponse(ApplicationEvent $event): void
    {
        /** @var Application $application */
        $application = $event->getApplication();

        if (!($application instanceof Application)) {
            return;
        }

        $debugBarOutput = $this->debugBar->getJavascriptRenderer()->render();

        // Fetch the body
        $body = (string) $application->getResponse()->getBody();

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
        $application->setResponse($application->getResponse()->withBody($stream));
    }

    public function handleError(ApplicationErrorEvent $event)
    {
        /** @var \DebugBar\DataCollector\ExceptionsCollector $collector */
        $collector = $this->debugBar->getCollector('exceptions');
        $collector->addThrowable($event->getError());
    }

    public function markAfterExecute(ApplicationEvent $event): void
    {
        /** @var \DebugBar\DataCollector\TimeDataCollector $collector */
        $collector = $this->debugBar->getCollector('time');

        $collector->stopMeasure('execution');
    }

    public function markBeforeExecute(ApplicationEvent $event): void
    {
        /** @var \DebugBar\DataCollector\TimeDataCollector $collector */
        $collector = $this->debugBar->getCollector('time');

        $collector->startMeasure('execution');
    }
}
