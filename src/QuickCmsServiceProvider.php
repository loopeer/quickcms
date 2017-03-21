<?php namespace Loopeer\QuickCms;

use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Loopeer\QuickCms\Services\Utils\GeneralUtil;
use Symfony\Component\Process\Process;

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

		// Publish config files
		$this->publishes([
			__DIR__.'/../config/quickCms.php' => config_path('quickCms.php'),
		], 'quickCms');

		$this->publishes([
			__DIR__.'/../config/quickApi.php' => config_path('quickApi.php'),
		], 'quickApi');

		$this->publishes([
			__DIR__.'/../public/backend' => public_path('loopeer/quickcms'),
		], 'public');

		$this->publishes([
			__DIR__.'/../database/migrations' => database_path('migrations'),
		], 'migrations');

		$langFiles = __DIR__ . '/../lang/*';
		$targetPath = base_path('resources/lang/');
		$process = new Process("cp -r $langFiles $targetPath");
		$process->run(function ($type, $buffer) {
			if (Process::ERR === $type) {
				return $this->error(trim($buffer));
			}
		});

		if (Schema::hasTable('selectors')) {
            foreach(GeneralUtil::allSelectorData() as $sk => $sv) {
                view()->share($sk, $sv);
            }
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
