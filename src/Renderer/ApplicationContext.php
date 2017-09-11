<?php

namespace BabDev\Website\Renderer;

use Joomla\Application\AbstractApplication;
use Joomla\Application\AbstractWebApplication;
use Symfony\Component\Asset\Context\ContextInterface;

/**
 * Joomla! application aware context.
 */
final class ApplicationContext implements ContextInterface
{
    /**
     * @var AbstractApplication
     */
    private $app;

    public function __construct(AbstractApplication $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function getBasePath()
    {
        return rtrim($this->app->get('uri.base.path'), '/');
    }

    /**
     * {@inheritdoc}
     */
    public function isSecure()
    {
        if ($this->app instanceof AbstractWebApplication) {
            return $this->app->isSslConnection();
        }

        return false;
    }
}
