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
     * Configuration instance.
     *
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

        // Load the configuration file into an object.
        $configObject = json_decode(file_get_contents($file));

        if ($configObject === null) {
            throw new \RuntimeException(sprintf('Unable to parse the configuration file %s.', $file));
        }

        $this->config = (new Registry)->loadObject($configObject);
    }

    /**
     * {@inheritdoc}
     */
    public function register(Container $container)
    {
        $container->set(
            'config',
            function (): Registry {
                return $this->config;
            }, true, true
        );
    }
}
