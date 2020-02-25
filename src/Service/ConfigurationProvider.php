<?php declare(strict_types=1);

namespace BabDev\Website\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Registry\Registry;

final class ConfigurationProvider implements ServiceProviderInterface
{
    private Registry $config;

    public function __construct()
    {
        // Set the configuration file path for the application.
        $file = JPATH_ROOT . '/etc/config.json';

        // Verify the configuration exists and is readable.
        if (!is_readable($file)) {
            throw new \RuntimeException('Configuration file does not exist or is unreadable.');
        }

        $this->config = (new Registry)->loadFile($file);
    }

    public function register(Container $container): void
    {
        $container->share('config', $this->config);
    }
}
