<?php declare(strict_types=1);

namespace BabDev\Website\Asset\Context;

use Joomla\Application\WebApplication;
use Symfony\Component\Asset\Context\ContextInterface;

final class ApplicationContext implements ContextInterface
{
    private WebApplication $app;

    public function __construct(WebApplication $app)
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
