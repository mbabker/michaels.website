<?php

namespace BabDev\Website\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Registry\Registry;

/**
 * Configuration service provider.
 */
class ConfigurationProvider implements ServiceProviderInterface
{
    /**
     * @var Registry
     */
    private $config;

    /**
     * @throws \RuntimeException
     */
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

    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $container->share(
            'config',
            function (): Registry {
                return $this->config;
            }, true
        );
    }
}
