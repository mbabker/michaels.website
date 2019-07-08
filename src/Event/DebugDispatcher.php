<?php declare(strict_types=1);

namespace BabDev\Website\Event;

use DebugBar\DebugBar;
use Joomla\Event\DispatcherInterface;
use Joomla\Event\EventInterface;
use Joomla\Event\SubscriberInterface;

final class DebugDispatcher implements DispatcherInterface
{
    /**
     * @var DebugBar
     */
    private $debugBar;

    /**
     * @var DispatcherInterface
     */
    private $dispatcher;

    public function __construct(DispatcherInterface $dispatcher, DebugBar $debugBar)
    {
        $this->debugBar   = $debugBar;
        $this->dispatcher = $dispatcher;
    }

    public function addListener(string $eventName, callable $callback, int $priority = 0): bool
    {
        return $this->dispatcher->addListener($eventName, $callback, $priority);
    }

    public function addSubscriber(SubscriberInterface $subscriber): void
    {
        $this->dispatcher->addSubscriber($subscriber);
    }

    public function dispatch(string $name, ?EventInterface $event = null): EventInterface
    {
        /** @var \DebugBar\DataCollector\TimeDataCollector $collector */
        $collector = $this->debugBar->getCollector('time');
        $label     = 'dispatching ' . $name;

        $collector->startMeasure($label);

        $event = $this->dispatcher->dispatch($name, $event);

        // Needed because the application's before respond event may be cut short
        if ($collector->hasStartedMeasure($label)) {
            $collector->stopMeasure($label);
        }

        return $event;
    }

    public function getListeners($event = null)
    {
        return $this->dispatcher->getListeners($event);
    }

    public function hasListener(callable $callback, $eventName = null)
    {
        return $this->dispatcher->hasListener($callback, $eventName);
    }

    public function removeListener(string $eventName, callable $listener): void
    {
        $this->dispatcher->removeListener($eventName, $listener);
    }

    public function removeSubscriber(SubscriberInterface $subscriber): void
    {
        $this->dispatcher->removeSubscriber($subscriber);
    }

    public function triggerEvent($event)
    {
        if (!method_exists($this->dispatcher, 'triggerEvent')) {
            throw new \RuntimeException('The decorated dispatcher does not implement the `triggerEvent` method.');
        }

        return $this->dispatcher->triggerEvent($event);
    }
}
