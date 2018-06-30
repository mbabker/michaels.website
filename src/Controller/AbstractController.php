<?php declare(strict_types=1);

namespace BabDev\Website\Controller;

use Joomla\Application\WebApplication;
use Joomla\Controller\ControllerInterface;
use Joomla\Input\Input;

abstract class AbstractController implements ControllerInterface
{
    /**
     * @var WebApplication
     */
    private $app;

    /**
     * @var Input
     */
    private $input;

    public function __construct(WebApplication $app, Input $input = null)
    {
        $this->app   = $app;
        $this->input = $input ?: $app->input;
    }

    protected function getApplication(): WebApplication
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

    public function unserialize($input): void
    {
        $input = unserialize($input);

        if (!($input instanceof Input)) {
            throw new \UnexpectedValueException(sprintf('%s would not accept a `%s`.', __METHOD__, \gettype($this->input)));
        }

        $this->input = $input;
    }
}
