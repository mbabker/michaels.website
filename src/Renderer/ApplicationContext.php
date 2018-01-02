<?php

namespace BabDev\Website\Renderer;

use Joomla\Application\AbstractApplication;
use Joomla\Application\AbstractWebApplication;
use Symfony\Component\Asset\Context\ContextInterface;

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

    public function getBasePath(): string
    {
        return rtrim($this->app->get('uri.base.path'), '/');
    }

    public function isSecure(): bool
    {
        if ($this->app instanceof AbstractWebApplication) {
            return $this->app->isSslConnection();
        }

        return false;
    }
}
