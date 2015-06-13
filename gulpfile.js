process.env.DISABLE_NOTIFIER = true;
var elixir = require('laravel-elixir');

var bower = function (path) {
	return 'vendor/bower_components/' + path;
}

elixir.config.sourcemaps = false;

elixir(function(mix) {
	mix
		.less('docs.less', 'public/css/docs.css')
		.less('git.less', 'public/css/git.css')

		.scripts('docs.js', 'public/js/docs.js')
		.scripts('git.js', 'public/js/git.js')

		.version(['css/docs.css', 'js/docs.js'])
		.version(['css/git.css', 'js/git.js']);
});
