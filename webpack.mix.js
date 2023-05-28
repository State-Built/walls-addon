let mix = require('laravel-mix');

mix.js('resources/js/gated.js', 'dist')
    .setPublicPath('dist');