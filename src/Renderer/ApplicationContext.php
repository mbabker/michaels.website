<?php

namespace BabDev\Website\Renderer;

use Joomla\Application\AbstractApplication;
use Joomla\Application\AbstractWebApplication;
use Symfony\Component\Asset\Context\ContextInterface;

/**
 * Joomla! application aware context.
 */
class ApplicationContext implements ContextInterface
{
    /**
     * Application object.
     *
     * @var AbstractApplication
     */
    private $app;

    /**
     * Constructor.
     *
     * @param AbstractApplication $app The application object
     */
    public function __construct(AbstractApplication $app)
    {
        $this->app = $app;
    }

    /**
     * Gets the base path.
     *
     * @return string The base path
     */
    public function getBasePath()
    {
        return rtrim($this->app->get('uri.base.path'), '/');
    }

    /**
     * Checks whether the request is secure or not.
     *
     * @return bool
     */
    public function isSecure()
    {
        if ($this->app instanceof AbstractWebApplication) {
            return $this->app->isSslConnection();
        }

        return false;
    }
}
