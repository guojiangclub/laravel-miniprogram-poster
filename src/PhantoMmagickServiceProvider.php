<?php

namespace iBrand\Poster;

use Illuminate\Support\ServiceProvider;

class PhantoMmagickServiceProvider extends ServiceProvider
{
	public function boot()
	{
		if ($this->app->runningInConsole()) {
			$this->publishes([__DIR__ . '/../config/config.php' => config_path('phantommagick.php')]);
		}
	}

	public function register()
	{
		$config = $this->app['config']->get('filesystems.disks', []);

		$this->app['config']->set('filesystems.disks', array_merge(config('phantommagick.disks', []), $config));
	}
}