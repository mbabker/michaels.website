<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

// Application constants
define('JPATH_ROOT',      dirname(__DIR__));
define('JPATH_TEMPLATES', JPATH_ROOT . '/templates');

// Ensure we've initialized Composer
if (!file_exists(JPATH_ROOT . '/vendor/autoload.php'))
{
	header('HTTP/1.1 500 Internal Server Error', null, 500);
	echo 'Composer is not set up properly, please run "composer install".';

	exit;
}

require JPATH_ROOT . '/vendor/autoload.php';

// Wrap in a try/catch so we can display an error if need be
try
{
	$container = (new Joomla\DI\Container)
		->registerServiceProvider(new BabDev\Website\Service\ConfigurationProvider)
		->registerServiceProvider(new BabDev\Website\Service\DatabaseProvider);

	// Set error reporting based on config
	$errorReporting = (int) $container->get('config')->get('errorReporting', 0);
	error_reporting($errorReporting);
}
catch (\Exception $e)
{
	header('HTTP/1.1 500 Internal Server Error', null, 500);
	header('Content-Type: text/html; charset=utf-8');
	echo 'An error occurred while booting the application: ' . $e->getMessage();

	exit;
}

// Execute the application
(new BabDev\Website\Application)
	->setContainer($container)
	->execute();
