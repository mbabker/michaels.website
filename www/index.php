<?php

// Application constants
define('JPATH_ROOT', dirname(__DIR__));
define('JPATH_TEMPLATES', JPATH_ROOT . '/templates');

// Ensure we've initialized Composer
if (!file_exists(JPATH_ROOT . '/vendor/autoload.php')) {
    header('HTTP/1.1 500 Internal Server Error', null, 500);
    echo 'Composer is not set up properly, please run "composer install".';

    exit;
}

require JPATH_ROOT . '/vendor/autoload.php';

(function () {
    // Wrap in a try/catch so we can display an error if need be
    try {
        $container = (new Joomla\DI\Container)
            ->registerServiceProvider(new BabDev\Website\Service\ConfigurationProvider)
            ->registerServiceProvider(new BabDev\Website\Service\TemplatingProvider)
            ->registerServiceProvider(new BabDev\Website\Service\WebApplicationProvider);

        // Set error reporting based on config
        $errorReporting = (int) $container->get('config')->get('errorReporting', 0);
        error_reporting($errorReporting);
    } catch (\Throwable $t) {
        error_log($t);

        header('HTTP/1.1 500 Internal Server Error', null, 500);
        header('Content-Type: text/html; charset=utf-8');
        echo 'An error occurred while booting the application: ' . $t->getMessage();

        exit;
    }

    // Execute the application
    try {
        $container->get(BabDev\Website\Application::class)->execute();
    } catch (\Throwable $t) {
        error_log($t);

        if (!headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error', null, 500);
            header('Content-Type: text/html; charset=utf-8');
        }

        echo 'An error occurred while executing the application: ' . $t->getMessage();

        exit;
    }
})();
