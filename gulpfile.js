process.env.DISABLE_NOTIFIER = true;

var elixir = require('laravel-elixir');

elixir.config.sourcemaps = false;

var bower = function (path) {
	return 'vendor/bower_components/' + path;
}

elixir(function(mix) {
	mix
        .copy(bower('highlightjs/highlight.pack.min.js'), 'public/js/vendor/highlight.js')
        .copy(bower('highlightjs/styles/obsidian.css'), 'public/css/vendor/highlightjs/obsidian.css')

        .less('docs.less', 'public/css/docs.css')
		.less('git.less', 'public/css/git.css')

		.scripts(['vendor/prism.js', 'docs.js'], 'public/js/docs.js')
		.scripts('git.js', 'public/js/git.js')

		.version(['css/docs.css', 'js/docs.js', 'css/git.css', 'js/git.js']);
});
