<?php

namespace BabDev\Website;

use Joomla\Controller\ControllerInterface;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\DI\Exception\KeyNotFoundException;

final class ControllerResolver implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function resolve(string $controller): ControllerInterface
    {
        if (!$this->getContainer()->has($controller)) {
            throw new KeyNotFoundException(
                sprintf(
                    'Controller `%s` has not been registered with the container.',
                    $controller
                )
            );
        }

        return $this->getContainer()->get($controller);
    }
}
