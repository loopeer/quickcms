<?php namespace Loopeer\QuickCms;

use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
			__DIR__ . '/../config/quickCms.php' => config_path('quickCms.php'),
		], 'quickCms');

		$this->publishes([
			__DIR__ . '/../config/quickApi.php' => config_path('quickApi.php'),
		], 'quickApi');

		$this->publishes([
			__DIR__ . '/../public/backend' => public_path('loopeer/quickcms'),
		], 'public');

		$this->publishes([
			__DIR__ . '/../database/migrations' => database_path('migrations'),
		], 'migrations');

		$this->publishes([
			__DIR__ . '/../lang' => base_path('resources/lang'),
		]);

        if (!Cache::has('app_logs_table')) {
            $table = collect(DB::select('SHOW TABLES'))->map(function ($table) {
                return $table->{'Tables_in_' . config('quickCms.app_logs_database')};
            })->filter(function ($tableName) {
                return strstr($tableName, 'app_logs') !== false;
            })->last();
            Cache::forever('app_logs_table', $table);
        }
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->commands(['Loopeer\QuickCms\Console\Commands\InstallCommand']);
		$this->commands(['Loopeer\QuickCms\Console\Commands\CreateBackendUser']);
		$this->commands(['Loopeer\QuickCms\Console\Commands\InitOperationPermission']);

        $this->app->bind(ResourceRegistrar::class, Routing\ResourceRegistrar::class);
    }

}
