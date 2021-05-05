<?php

use App\Http\Controllers\HomepageController;
use App\Http\Controllers\ViewBlogIndexController;
use App\Http\Controllers\ViewBlogPostController;
use App\Http\Controllers\ViewSitemapController;
use Illuminate\Routing\Router;

/** @var Router $router */
$router->get(
    '/',
    HomepageController::class
)->name('homepage');

$router->view('/about', 'about')->name('about');
$router->view('/privacy', 'privacy')->name('privacy');

$router->get(
    '/blog',
    ViewBlogIndexController::class
)->name('blog.index');

$router->get(
    '/blog/page/{page}',
    ViewBlogIndexController::class
)
    ->name('blog.index.paginated')
    ->whereNumber('page');

$router->get(
    '/blog/{slug}',
    ViewBlogPostController::class
)->name('blog.show');

$router->get(
    '/sitemap.xml',
    ViewSitemapController::class
);
