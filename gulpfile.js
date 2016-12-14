const elixir = require('laravel-elixir');

require('laravel-elixir-postcss');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix
        .copy(
            'node_modules/jquery/dist/jquery.js',
            'resources/assets/js/vendor/jquery.js'
        )
        .copy(
            'node_modules/smoothstate/src/jquery.smoothState.js',
            'resources/assets/js/vendor/smoothstate.js'
        )
        .copy(
            'node_modules/blueimp-md5/js/md5.min.js',
            'resources/assets/js/vendor/md5.js'
        )
        .copy(
            'node_modules/jQuery-QueryBuilder/dist/js/query-builder.standalone.js',
            'resources/assets/js/vendor/jquery-querybuilder.js'
        )
        .copy(
            'node_modules/garden/dist/js/garden.min.js',
            'resources/assets/js/vendor/garden.js'
        )
        .copy(
            'node_modules/garden/dist/css/garden.min.css',
            'resources/assets/less/vendor/garden.less'
        )
        .copy(
            'node_modules/garden/dist/fonts',
            'public/fonts'
        )
        .less(
            'main.less',
            'public/css/main.css'
        )
        .scripts([
            'vendor/jquery.js',
            'vendor/**/*.js',
            '**/*.js'
        ]);
});
