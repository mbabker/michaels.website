<?php declare(strict_types=1);

namespace BabDev\Website\Asset;

use Symfony\Component\Asset\Context\ContextInterface;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\PathPackage as BasePathPackage;
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

final class MixPathPackage extends BasePathPackage
{
    /**
     * @var Package
     */
    private $decoratedPackage;

    public function __construct(
        Package $decoratedPackage,
        $basePath,
        VersionStrategyInterface $versionStrategy,
        ContextInterface $context = null
    ) {
        parent::__construct($basePath, $versionStrategy, $context);

        $this->decoratedPackage = $decoratedPackage;
    }

    public function getUrl($path): string
    {
        if ($this->isAbsoluteUrl($path)) {
            return $path;
        }

        $editedPath = ltrim($path, '/');

        $versionedPath = $this->getVersionStrategy()->applyVersion("/$editedPath");

        if ($versionedPath === $path) {
            return $this->decoratedPackage->getUrl($path);
        }

        return $this->getBasePath() . ltrim($versionedPath, '/');
    }
}
