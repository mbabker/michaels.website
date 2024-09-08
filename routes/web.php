<?php

use App\Http\Controllers\ViewBlogIndexController;
use App\Http\Controllers\ViewBlogPostController;
use App\Http\Controllers\ViewSitemapController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'about')->name('homepage');

Route::redirect('/about', '/');
Route::view('/privacy', 'privacy')->name('privacy');

Route::get('/blog', ViewBlogIndexController::class)->name('blog.index');

Route::get('/blog/page/{page}', ViewBlogIndexController::class)->name('blog.index.paginated')
    ->whereNumber('page');

Route::get('/blog/{slug}', ViewBlogPostController::class)->name('blog.show');

Route::get('/sitemap.xml', ViewSitemapController::class);

Route::feeds('feeds');
