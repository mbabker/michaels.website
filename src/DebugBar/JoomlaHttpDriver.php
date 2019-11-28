<?php declare(strict_types=1);

namespace BabDev\Website\DebugBar;

use DebugBar\HttpDriverInterface;
use Joomla\Application\AbstractWebApplication;

final class JoomlaHttpDriver implements HttpDriverInterface
{
    public function __construct(AbstractWebApplication $application)
    {
        $this->application = $application;
    }

    public function setHeaders(array $headers): void
    {
        foreach ($headers as $name => $value) {
            $this->application->setHeader($name, $value);
        }
    }

    public function isSessionStarted()
    {
        // This application has no session integration
        return false;
    }

    public function setSessionValue($name, $value): void
    {
        // This application has no session integration
    }

    public function hasSessionValue($name)
    {
        // This application has no session integration
        return false;
    }

    public function getSessionValue($name): void
    {
        // This application has no session integration
        return;
    }

    public function deleteSessionValue($name): void
    {
        // This application has no session integration
    }
}
