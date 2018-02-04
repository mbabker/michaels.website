<?php

namespace BabDev\Website\EventListener;

use DebugBar\DebugBar;
use Joomla\Application\AbstractWebApplication;
use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationEvent;
use Joomla\Event\Priority;
use Joomla\Event\SubscriberInterface;

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
        ];
    }

    public function handleDebugResponse(ApplicationEvent $event): void
    {
        /** @var AbstractWebApplication $application */
        $application = $event->getApplication();

        if (!($application instanceof AbstractWebApplication)) {
            return;
        }

        $debugBarOutput = $this->debugBar->getJavascriptRenderer()->render();

        // Fetch the body
        $body = $application->getBody();

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
        $application->setBody($body);
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
