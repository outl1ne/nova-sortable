/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.{js,vue}',
  ],
  prefix: 'nova-sortable-',
  corePlugins: {
    preflight: false,
  }
}
