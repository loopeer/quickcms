<?php namespace Loopeer\QuickCms;

use Illuminate\Support\ServiceProvider;

class QuickCmsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot() {
		$this->app->router->group(['namespace' => 'Loopeer\QuickCms\Http\Controllers'], function()
		{
			require __DIR__.'/Http/route.php';
		});

		$this->loadViewsFrom(__DIR__.'/../views', 'backend');

		$this->publishes([
			__DIR__.'/../public/backend' => public_path('loopeer/quickcms'),
		], 'public');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->commands(['Loopeer\QuickCms\Console\Commands\InstallCommand']);
    }

}
