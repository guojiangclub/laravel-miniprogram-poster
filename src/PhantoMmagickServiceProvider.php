<?php

namespace iBrand\Miniprogram\Poster;

use Illuminate\Support\ServiceProvider;

class PhantoMmagickServiceProvider extends ServiceProvider
{
	public function boot()
	{
		if ($this->app->runningInConsole()) {
			$this->publishes([__DIR__ . '/../config/config.php' => config_path('ibrand/miniprogram-poster.php')], 'config');
		}
	}

	public function register()
	{
		$filesystems = $this->app['config']->get('filesystems.disks', []);

		$this->app['config']->set('filesystems.disks', array_merge(config('ibrand.miniprogram-poster.disks', []), $filesystems));
	}
}