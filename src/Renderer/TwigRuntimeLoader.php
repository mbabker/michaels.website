<?php

namespace BabDev\Website\Renderer;

use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;

/**
 * Twig runtime loader.
 */
class TwigRuntimeLoader implements \Twig_RuntimeLoaderInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function load($class)
    {
        if ($this->getContainer()->exists($class)) {
            return $this->getContainer()->get($class);
        }
    }
}
