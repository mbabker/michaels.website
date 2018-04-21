<?php

namespace BabDev\Website\DebugBar;

use DebugBar\HttpDriverInterface;
use Joomla\Application\AbstractWebApplication;

final class JoomlaHttpDriver implements HttpDriverInterface
{
    /**
     * @var AbstractWebApplication
     */
    private $application;

    public function __construct(AbstractWebApplication $application)
    {
        $this->application = $application;
    }

    public function setHeaders(array $headers)
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

    public function setSessionValue($name, $value)
    {
        // This application has no session integration
    }

    public function hasSessionValue($name)
    {
        // This application has no session integration
        return false;
    }

    public function getSessionValue($name)
    {
        // This application has no session integration
        return;
    }

    public function deleteSessionValue($name)
    {
        // This application has no session integration
    }
}
