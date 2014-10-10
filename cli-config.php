<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;

// Application constants
define('JPATH_ROOT',      __DIR__);
define('JPATH_TEMPLATES', JPATH_ROOT . '/templates');

// Ensure we've initialized Composer
if (!file_exists(JPATH_ROOT . '/vendor/autoload.php'))
{
	fwrite(STDOUT, "Composer is not set up properly, please run 'composer install'.\n");

	exit;
}

require JPATH_ROOT . '/vendor/autoload.php';

$container = (new Joomla\DI\Container)
	->registerServiceProvider(new BabDev\Website\Service\ConfigurationProvider)
	->registerServiceProvider(new \BabDev\Website\Service\EntityManagerProvider);

$entityManager = $container->get('em');

return ConsoleRunner::createHelperSet($entityManager);
