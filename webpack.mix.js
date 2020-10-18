const mix = require('laravel-mix');
require('laravel-mix-purgecss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.postCss('resources/css/app.css', 'public/css')
    .options({
        postCss: [require('tailwindcss')]
    })
    .purgeCss({
        extend: {
            whitelist: [
                'blockquote',
            ],
            whitelistPatterns: [
                // Images in content
                /img-thumbnail.*/,
                // Utilities in content
                /float-right.*/,
            ],
        }
    });

mix.copy('node_modules/@fortawesome/fontawesome-free/js/all.min.js', 'public/js/fontawesome.min.js');

if (Mix.inProduction()) {
    mix.version();
}
