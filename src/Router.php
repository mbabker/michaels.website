<?php

namespace BabDev\Website;

use Joomla\Controller\ControllerInterface;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Router\Router as BaseRouter;

/**
 * Application router.
 */
class Router extends BaseRouter implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    protected function fetchController($name): ControllerInterface
    {
        // Derive the controller class name.
        $class = $this->controllerPrefix . ucfirst($name);

        // If the controller class does not exist panic.
        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf('Unable to locate controller `%s`.', $class), 404);
        }

        // If the controller does not follows the implementation.
        if (!is_subclass_of($class, 'Joomla\\Controller\\ControllerInterface')) {
            throw new \RuntimeException(
                sprintf('Invalid Controller. Controllers must implement Joomla\Controller\ControllerInterface. `%s`.', $class), 500
            );
        }

        // Instantiate the controller.
        return $this->getContainer()->get($class);
    }
}
