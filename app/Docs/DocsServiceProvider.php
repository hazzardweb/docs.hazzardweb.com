<?php

namespace HazzardWeb\Docs;

use Parsedown;
use PHPGit\Git;
use Illuminate\Support\ServiceProvider;

class DocsServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('HazzardWeb\Docs\DocsRepositoryContract', function() {
			return $this->createDriver();
		});
	}

	protected function createDriver()
	{
		$config = $this->app['config']['docs'];

		if ($config['driver'] === 'git') {
			return (new GitDocsRepository($config, $this->app['files'], $this->app['cache.store'], new Parsedown))
					->setGit(new Git);
		}

		return new FlatDocsRepository($config, $this->app['files'], $this->app['cache.store'], new Parsedown);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [DocsRepositoryContract::class];
	}
}
