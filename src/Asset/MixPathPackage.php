<?php

namespace BabDev\Website\Asset;

use Symfony\Component\Asset\Context\ContextInterface;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\PathPackage as BasePathPackage;
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

/**
 * Extended path package for resolving assets from a Laravel Mix manifest.
 */
class MixPathPackage extends BasePathPackage
{
    /**
     * @var Package
     */
    private $decoratedPackage;

    /**
     * @param Package                  $decoratedPackage
     * @param string                   $basePath
     * @param VersionStrategyInterface $versionStrategy
     * @param ContextInterface         $context
     */
    public function __construct(
        Package $decoratedPackage,
        $basePath,
        VersionStrategyInterface $versionStrategy,
        ContextInterface $context = null
    ) {
        parent::__construct($basePath, $versionStrategy, $context);

        $this->decoratedPackage = $decoratedPackage;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($path)
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
