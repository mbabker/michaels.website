<?php declare(strict_types=1);

namespace BabDev\Website\Renderer;

use Joomla\Application\AbstractWebApplication;
use Symfony\Component\Asset\Context\ContextInterface;

final class ApplicationContext implements ContextInterface
{
    /**
     * @var AbstractWebApplication
     */
    private $app;

    public function __construct(AbstractWebApplication $app)
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
