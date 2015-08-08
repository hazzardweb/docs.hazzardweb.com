process.env.DISABLE_NOTIFIER = true;

var elixir = require('laravel-elixir');

elixir.config.sourcemaps = false;

var bower = function (path) {
	return 'vendor/bower_components/' + path;
}

elixir(function(mix) {
	mix.less('docs.less', 'public/css/docs.css')
		.less('git.less', 'public/css/git.css')

		.scripts('docs.js', 'public/js/docs.js')
		.scripts('git.js', 'public/js/git.js')

		.version(['css/docs.css', 'js/docs.js', 'css/git.css', 'js/git.js']);
});
