const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .scripts([
        './node_modules/startbootstrap-sb-admin-2/vendor/jquery/jquery.min.js',
        './node_modules/startbootstrap-clean-blog/vendor/bootstrap/js/bootstrap.bundle.js',
        './node_modules/startbootstrap-clean-blog/js/clean-blog.min.js',
    ], 'public/js/blog.js')
    .scripts([
        './node_modules/startbootstrap-sb-admin-2/vendor/jquery/jquery.min.js',
        './node_modules/startbootstrap-sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js',
        './node_modules/startbootstrap-sb-admin-2/vendor/jquery-easing/jquery.easing.js',
        './node_modules/startbootstrap-sb-admin-2/js/sb-admin-2.min.js',
        './node_modules/startbootstrap-sb-admin-2/vendor/chart.js/Chart.min.js',
    ], 'public/js/admin.js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/frontend.sass', 'public/css')
    .styles([
        './node_modules/startbootstrap-clean-blog/vendor/bootstrap/css/bootstrap.min.css',
        './node_modules/@fortawesome/fontawesome-free/css/all.min.css',
        './node_modules/startbootstrap-clean-blog/css/clean-blog.min.css',
        'public/css/frontend.css',
    ], 'public/css/blog.css')
    .styles([
        './node_modules/@fortawesome/fontawesome-free/css/all.min.css',
        './node_modules/startbootstrap-sb-admin-2/css/sb-admin-2.min.css',
], 'public/css/admin.css')
    .copy('./node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts');
