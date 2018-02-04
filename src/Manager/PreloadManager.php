<?php

namespace BabDev\Website\Manager;

use Fig\Link\GenericLinkProvider;
use Fig\Link\Link;
use Psr\Link\EvolvableLinkProviderInterface;

final class PreloadManager
{
    /**
     * @var EvolvableLinkProviderInterface
     */
    protected $linkProvider;

    public function __construct(EvolvableLinkProviderInterface $linkProvider = null)
    {
        $this->linkProvider = $linkProvider ?: new GenericLinkProvider;
    }

    public function getLinkProvider(): EvolvableLinkProviderInterface
    {
        return $this->linkProvider;
    }

    public function setLinkProvider(EvolvableLinkProviderInterface $linkProvider): void
    {
        $this->linkProvider = $linkProvider;
    }

    public function preload(string $uri, array $attributes = []): void
    {
        $this->link($uri, 'preload', $attributes);
    }

    public function dnsPrefetch(string $uri, array $attributes = []): void
    {
        $this->link($uri, 'dns-prefetch', $attributes);
    }

    public function preconnect(string $uri, array $attributes = []): void
    {
        $this->link($uri, 'preconnect', $attributes);
    }

    public function prefetch(string $uri, array $attributes = []): void
    {
        $this->link($uri, 'prefetch', $attributes);
    }

    public function prerender(string $uri, array $attributes = []): void
    {
        $this->link($uri, 'prerender', $attributes);
    }

    private function link(string $uri, string $rel, array $attributes = []): void
    {
        $link = new Link($rel, $uri);

        foreach ($attributes as $key => $value) {
            $link = $link->withAttribute($key, $value);
        }

        $this->setLinkProvider($this->getLinkProvider()->withLink($link));
    }
}
