<?php declare(strict_types=1);

namespace BabDev\Website\EventListener;

use DebugBar\DebugBar;
use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationErrorEvent;
use Joomla\Application\Event\ApplicationEvent;
use Joomla\Application\WebApplication;
use Joomla\Event\Priority;
use Joomla\Event\SubscriberInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;

final class DebugSubscriber implements SubscriberInterface
{
    private DebugBar $debugBar;

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
        /** @var WebApplication $application */
        $application = $event->getApplication();

        // Ensure responses are not cached
        $application->allowCache(false);

        if ($application->getResponse() instanceof HtmlResponse) {
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
                    $body = substr_replace($body, $debugBarOutput . '</body>', $pos, \strlen('</body>'));
                }
            }

            // Reset the body
            $application->setBody($body);
        } elseif ($application instanceof RedirectResponse) {
            $this->debugBar->stackData();
        } else {
            $this->debugBar->sendDataInHeaders();
        }
    }

    public function handleError(ApplicationErrorEvent $event): void
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
