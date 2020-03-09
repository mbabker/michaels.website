<?php declare(strict_types=1);

namespace BabDev\Website\Service;

use Joomla\DI\Container;
use Joomla\DI\Exception\DependencyResolutionException;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Registry\Registry;

final class ConfigurationProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->share(
            'config',
            static function (): Registry {
                // Set the configuration file path for the application.
                $file = JPATH_ROOT . '/etc/config.json';

                // Verify the configuration exists and is readable.
                if (!is_readable($file)) {
                    throw new DependencyResolutionException('Configuration file does not exist or is unreadable.');
                }

                return (new Registry)->loadFile($file);
            }
        );
    }
}
