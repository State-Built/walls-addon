let mix = require('laravel-mix');

mix.js('resources/js/walls.js', 'dist')
    .setPublicPath('dist');