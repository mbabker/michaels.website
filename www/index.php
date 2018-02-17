<?php

// Application constants
define('APP_START', microtime(true));
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
            ->registerServiceProvider(new BabDev\Website\Service\EventProvider)
            ->registerServiceProvider(new BabDev\Website\Service\SerializerProvider)
            ->registerServiceProvider(new BabDev\Website\Service\TemplatingProvider)
            ->registerServiceProvider(new BabDev\Website\Service\WebApplicationProvider)
            ->registerServiceProvider(new Joomla\Preload\Service\PreloadProvider);

        // Conditionally include the DebugBar service provider based on the app being in debug mode
        if ((bool) $container->get('config')->get('debug', false)) {
            $container->registerServiceProvider(new BabDev\Website\Service\DebugBarProvider);
        }

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

    // There is a circular dependency in building the HTTP driver while the application is being resolved, so it'll need to be set here for now
    if ($container->has(\DebugBar\DebugBar::class)) {
        /** @var \DebugBar\DebugBar $debugBar */
        $debugBar = $container->get(\DebugBar\DebugBar::class);
        $debugBar->setHttpDriver($container->get(\BabDev\Website\DebugBar\JoomlaHttpDriver::class));

        /** @var \DebugBar\DataCollector\TimeDataCollector $collector */
        $collector = $debugBar->getCollector('time');
        $collector->addMeasure('initialization', APP_START, microtime(true));
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
