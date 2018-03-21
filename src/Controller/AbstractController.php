<?php

namespace BabDev\Website\Controller;

use BabDev\Website\Application;
use Joomla\Controller\ControllerInterface;
use Joomla\Input\Input;

abstract class AbstractController implements ControllerInterface
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var Input
     */
    private $input;

    public function __construct(Application $app, Input $input = null)
    {
        $this->app   = $app;
        $this->input = $input ?: $app->input;
    }

    protected function getApplication(): Application
    {
        return $this->app;
    }

    protected function getInput(): Input
    {
        return $this->input;
    }

    public function serialize()
    {
        return serialize($this->getInput());
    }

    public function unserialize($input)
    {
        $input = unserialize($input);

        if (!($input instanceof Input)) {
            throw new \UnexpectedValueException(sprintf('%s would not accept a `%s`.', __METHOD__, gettype($this->input)));
        }

        $this->input = $input;
    }
}
