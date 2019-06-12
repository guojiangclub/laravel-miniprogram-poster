<?php

/*
 * This file is part of ibrand/laravel-miniprogram-poster.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Miniprogram\Poster;

use Illuminate\Support\ServiceProvider;
use HeadlessChromium\BrowserFactory;


class PhantoMmagickServiceProvider extends ServiceProvider
{
	public function boot()
	{
		if ($this->app->runningInConsole()) {
			$this->publishes([__DIR__ . '/../config/config.php' => config_path('ibrand/miniprogram-poster.php')], 'config');

			if (!class_exists('CreatePosterTables')) {
				$timestamp = date('Y_m_d_His', time());
				$this->publishes([
					__DIR__ . '/../migrations/create_poster_tables.php.stub' => database_path('migrations/' . $timestamp . '_create_poster_tables.php'),
				], 'migrations');
			}
		}

	}

	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__ . '/../config/config.php', 'ibrand.miniprogram-poster'
		);

		$filesystems = $this->app['config']->get('filesystems.disks', []);

		$this->app['config']->set('filesystems.disks', array_merge(config('ibrand.miniprogram-poster.disks'), $filesystems));

		$this->app->register(\Overtrue\LaravelFilesystem\Qiniu\QiniuStorageServiceProvider::class);

        $this->app->singleton('MiniProgramShareImg', function($app) {

            return new MiniProgramShareImg(config('ibrand.miniprogram-poster'),new BrowserFactory(config('ibrand.miniprogram-poster.chromeBinaries')));
        });
	}

    public function provides()
    {
        return  [MiniProgramShareImg::class,'MiniProgramShareImg'];
    }
}
