let mix = require('laravel-mix');

// Configure base path for mix stuff going to web
mix.setPublicPath('www/media/');

mix.setResourceRoot('/media/');

// Core app JS
//mix.js('assets/js/template.js', 'js');

// Core app CSS
mix.sass('assets/scss/template.scss', 'css');

// Version assets
mix.version();
