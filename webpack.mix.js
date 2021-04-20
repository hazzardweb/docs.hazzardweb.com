const mix = require('laravel-mix')

mix
  .copy('node_modules/bootstrap-sass/assets/fonts/bootstrap/*.*', 'public/fonts')
  .js('resources/assets/js/app.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css')
  .version()
  .disableNotifications()
