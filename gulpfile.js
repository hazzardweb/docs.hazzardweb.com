process.env.DISABLE_NOTIFIER = true;

var elixir = require('laravel-elixir');

elixir.config.sourcemaps = !elixir.config.production;

elixir(function (mix) {
	mix.sass('app.scss')
		.scripts(['vendor', 'app.js'], 'public/js/app.js')
		.version(['css/app.css', 'js/app.js'])
        .copy('node_modules/bootstrap-sass/assets/fonts/bootstrap/*.*', 'public/build/fonts');
});
