<?php

namespace iBrand\Poster\Test;

use Orchestra\Testbench\TestCase;

abstract class BaseTest extends TestCase
{

	/**
	 * set up test.
	 */
	protected function setUp()
	{
		parent::setUp();

	}

	/**
	 * @param \Illuminate\Foundation\Application $app
	 */
	protected function getEnvironmentSetUp($app)
	{

        $app['config']->set('ibrand.miniprogram-poster', require __DIR__.'/../config/config.php');


		$app['config']->set('filesystems.disks', $app['config']->get('ibrand.miniprogram-poster.disks'), $app['config']->get('filesystems.disks'));
	}

	/**
	 * @param \Illuminate\Foundation\Application $app
	 *
	 * @return array
	 */
	protected function getPackageProviders($app)
	{
		return [
			\iBrand\Miniprogram\Poster\PhantoMmagickServiceProvider::class,
		];
	}
}