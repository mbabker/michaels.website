<?php
/**
 * Build a ZIP package to deploy to the production server
 *
 * This script strips all local environment data and non-production resources from the filesystem before running the
 * `zip` command.  It should be run after `composer install --no-dev -o -a`. This script WILL remove the `etc/config.json`
 * file when it is run.  On the provisioning server the git repo is copied to a staging space before files start to be
 * removed to prevent accidental removal of the in-use parameters file.
 *
 * This script must be run on a Linux platform.  Sorry Windoze.
 */

$baseDir    = __DIR__;
$stagingDir = $baseDir . '/staging';

chdir($baseDir);

echo "Provisioning staging\n";

if (is_dir($stagingDir)) {
    system('rm -rf staging');
}

mkdir($stagingDir, 0755);

// Copy everything into staging
system('cp -r pages/ staging/pages/');
system('cp -r src/ staging/src/');
system('cp -r templates/ staging/templates/');
system('cp -r vendor/ staging/vendor/');
system('cp -r www/ staging/www/');

// Append the live .htaccess contents to the base .htaccess
file_put_contents("$stagingDir/www/.htaccess", file_get_contents("$baseDir/.htaccess.live"), FILE_APPEND);

chdir($stagingDir);

echo "Removing extra files\n";

// Removes the .git sources
system('rm -rf .git*');

// Removes Mac .DS_Store files, .git sources, PHP-CS Fixer configuration files, Scrutinizer configuration files, Travis-CI configuration files, Changelogs, GitHub Contributing Guidelines, Composer manifests, README files, and PHPUnit configurations
system('find . -name .coveralls.yml | xargs rm -rf -');
system('find . -name .DS_Store | xargs rm -rf -');
system('find . -name .editorconfig | xargs rm -rf -');
system('find . -name .git* | xargs rm -rf -');
system('find . -name .php_cs | xargs rm -rf -');
system('find . -name .php_cs.dist | xargs rm -rf -');
system('find . -name .scrutinizer.yml | xargs rm -rf -');
system('find . -name .travis.yml | xargs rm -rf -');
system('find . -name CHANGELOG*.md | xargs rm -rf -');
system('find . -name composer.json | xargs rm -rf -');
system('find . -name composer.lock | xargs rm -rf -');
system('find . -name CONTRIBUTING.md | xargs rm -rf -');
system('find . -name easy-coding-standard.neon | xargs rm -rf -');
system('find . -name Makefile | xargs rm -rf -');
system('find . -name phpunit.xml | xargs rm -rf -');
system('find . -name phpunit.xml.dist | xargs rm -rf -');
system('find . -name README.md | xargs rm -rf -');
system('find . -name readme.md | xargs rm -rf -');
system('find . -name UPGRADE*.md | xargs rm -rf -');

// Remove debug assets if existing
system('rm -f www/media/css/debugbar.css');
system('rm -f www/media/js/debugbar.js');

echo "Cleaning vendors\n";

// fig/link-util
system('rm -rf vendor/fig/link-util/test');
system('rm -rf vendor/fig/link-util/phpcs.xml');

// joomla/*
system('rm -rf vendor/joomla/*/.travis');
system('rm -rf vendor/joomla/*/docs');
system('rm -rf vendor/joomla/*/Tests');
system('rm -rf vendor/joomla/*/tests');
system('rm -rf vendor/joomla/*/ruleset.xml');

// nesbot/carbon
system('rm -rf vendor/nesbot/carbon/build.php');

// pagerfanta/pagerfanta
system('rm -rf vendor/pagerfanta/pagerfanta/code_of_conduct.md');

// phpdocumentor/*
system('rm -rf vendor/phpdocumentor/*/tests');
system('rm -rf vendor/phpdocumentor/*/phpmd.xml.dist');

// symfony/*
system('rm -rf vendor/symfony/*/Tests');

// twig/*
system('rm -rf vendor/twig/*/doc');
system('rm -rf vendor/twig/*/ext');
system('rm -rf vendor/twig/*/test');
system('rm -rf vendor/twig/*/CHANGELOG');
system('rm -rf vendor/twig/*/README.rst');

// webmozart/assert
system('rm -rf vendor/webmozart/assert/tests');
system('rm -rf vendor/webmozart/assert/.composer-auth.json');
system('rm -rf vendor/webmozart/assert/.styleci.yml');
system('rm -rf vendor/webmozart/assert/appveyor.yml');

// zendframework/zend-diactoros
system('rm -rf vendor/zendframework/zend-diactoros/CONDUCT.md');
system('rm -rf vendor/zendframework/zend-diactoros/mkdocs.yml');

echo "Packaging the site\n";
system('zip -r ../site.zip . > /dev/null');
