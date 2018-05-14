let mix = require('laravel-mix');

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
            require('autoprefixer')({ browsers: 'last 2 versions' })
        ]
    });

// Copy third party resources
mix.copy('node_modules/cookieconsent/build/cookieconsent.min.css', 'www/media/css/cookieconsent.min.css');

// Version assets
mix.version();
