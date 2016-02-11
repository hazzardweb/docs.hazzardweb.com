process.env.DISABLE_NOTIFIER = true;

var elixir = require('laravel-elixir');

elixir.config.sourcemaps = false;

elixir(function (mix) {
	mix.less('app.less')
		.scripts(['vendor', 'app.js'], 'public/js/app.js')
		.version(['css/app.css', 'js/app.js']);
});
