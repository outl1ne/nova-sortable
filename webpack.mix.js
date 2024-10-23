let mix = require('laravel-mix');
let path = require('path');

mix.extend('nova', new require('./vendor/laravel/nova-devtool'))

mix
  .setPublicPath('dist')
  .js('resources/js/entry.js', 'js')
  .vue({ version: 3 })
  .postCss('resources/css/tool.css', 'css', [require('tailwindcss')])
  .alias({
    'laravel-nova-mixins': path.join(__dirname, 'vendor/laravel/nova/resources/js/mixins/index.js'),
    '@': path.join(__dirname, 'vendor/laravel/nova/resources/js/'),
  })
  .nova('outl1ne/nova-sortable');
