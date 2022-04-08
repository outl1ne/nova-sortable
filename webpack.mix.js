const mix = require('laravel-mix');

require('./nova.mix')

mix.setPublicPath('dist')
  .js('resources/js/tool.js', 'js')
  .vue({version: 3})
  .nova('optimistdigital/nova-sortable');
