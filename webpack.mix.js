const mix = require('laravel-mix');
require('laravel-mix-purgecss');

// Configure base path for mix stuff going to web
mix.setPublicPath('www/media/');

mix.setResourceRoot('/media/');

// Core app JS
mix.js('assets/js/template.js', 'js');

// Core app CSS
mix
    .sass('assets/scss/template.scss', 'css')
    .options({
        postCss: [
            require('autoprefixer')(),
        ],
    })
    .purgeCss({
        extend: {
            content: ['templates/**/*.twig'],
            whitelistPatterns: [
                // Bootstrap Pagination
                /pagination.*/, /page-item.*/, /page-link.*/,
                // JavaScript menu enhancement
                /is-fixed.*/, /is-visible.*/,
            ],
        }
    })
;

// Version assets
mix.version();
