<?php

namespace BabDev\Website\Renderer;

use BabDev\Website\Application;
use Symfony\Component\Asset\Context\ContextInterface;

final class ApplicationContext implements ContextInterface
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getBasePath(): string
    {
        return rtrim($this->app->get('uri.base.path'), '/');
    }

    public function isSecure(): bool
    {
        return $this->app->isSslConnection();
    }
}
