<?php declare(strict_types=1);

namespace BabDev\Website\Twig;

use BabDev\Website\Twig\Service\AssetService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class AssetExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset', [AssetService::class, 'getAssetUrl']),
            new TwigFunction('preload', [AssetService::class, 'preloadAsset']),
        ];
    }
}
