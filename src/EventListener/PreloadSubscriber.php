<?php

namespace BabDev\Website\EventListener;

use BabDev\Website\Manager\PreloadManager;
use Joomla\Application\AbstractWebApplication;
use Joomla\Application\ApplicationEvents;
use Joomla\Application\Event\ApplicationEvent;
use Joomla\Event\SubscriberInterface;
use Psr\Link\EvolvableLinkProviderInterface;
use Symfony\Component\WebLink\HttpHeaderSerializer;

final class PreloadSubscriber implements SubscriberInterface
{
    /**
     * @var PreloadManager
     */
    private $preloadManager;

    public function __construct(PreloadManager $preloadManager)
    {
        $this->preloadManager = $preloadManager;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ApplicationEvents::BEFORE_RESPOND => 'sendLinkHeader',
        ];
    }

    public function sendLinkHeader(ApplicationEvent $event): void
    {
        /** @var AbstractWebApplication $application */
        $application = $event->getApplication();

        if (!($application instanceof AbstractWebApplication)) {
            return;
        }

        $linkProvider = $this->preloadManager->getLinkProvider();

        if ($linkProvider && $linkProvider instanceof EvolvableLinkProviderInterface && $links = $linkProvider->getLinks()) {
            $application->setHeader('Link', (new HttpHeaderSerializer)->serialize($links));
        }
    }
}
