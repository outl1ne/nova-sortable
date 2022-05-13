let mix = require('laravel-mix');
let path = require('path');

require('./nova.mix');

mix
  .setPublicPath('dist')
  .js('resources/js/tool.js', 'js')
  .vue({ version: 3 })
  .nova('outl1ne/nova-sortable')
  .alias({
    'laravel-nova': path.join(__dirname, 'vendor/laravel/nova/resources/js/mixins/packages.js'),
    '@': path.join(__dirname, 'resources/js/'),
  });
