<?php declare(strict_types=1);

namespace BabDev\Website\Twig\Service;

use Joomla\Preload\PreloadManager;
use Symfony\Component\Asset\Packages;

final class AssetService
{
    private Packages $packages;

    private PreloadManager $preloadManager;

    public function __construct(Packages $packages, PreloadManager $preloadManager)
    {
        $this->packages       = $packages;
        $this->preloadManager = $preloadManager;
    }

    public function getAssetUrl(string $asset, ?string $package = null): string
    {
        return $this->packages->getUrl($asset, $package);
    }

    public function preloadAsset(string $uri, string $linkType = 'preload', array $attributes = []): string
    {
        $this->preloadManager->link($uri, $linkType, $attributes);

        return $uri;
    }
}
