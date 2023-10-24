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
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();


mix.js('resources/js/vue/tarimas/main.js', 'public/js/tarimas.js').vue()

    
mix.styles([
    'resources/template/css/page-auth.css'
], 'public/css/page-auth.css');

mix.js([
    'resources/template/js/helpers.js',
    'resources/template/js/config.js',
    'resources/template/js/perfect-scrollbar.js',
    'resources/template/js/menu.js',
    'resources/template/js/main.js'
], 'public/js/generales.js');