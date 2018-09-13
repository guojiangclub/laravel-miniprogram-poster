<?php

namespace iBrand\Poster\Test;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Orchestra\Testbench\TestCase;

abstract class BaseTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * set up test.
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->loadMigrationsFrom(__DIR__ . '/database');
	}

	/**
	 * @param \Illuminate\Foundation\Application $app
	 */
	protected function getEnvironmentSetUp($app)
	{
		// Setup default database to use sqlite :memory:
		$app['config']->set('database.default', 'testing');
		$app['config']->set('database.connections.testing', [
			'driver'   => 'sqlite',
			'database' => ':memory:',
		]);
		$app['config']->set('repository.cache.enabled', true);

		$shareConfig = require __DIR__ . '/config/phantommagick.php';

		$app['config']->set('phantommagick', $shareConfig);

		$app['config']->set('filesystems.disks', array_merge($shareConfig['disks'], $app['config']->get('filesystems.disks')));
	}

	/**
	 * @param \Illuminate\Foundation\Application $app
	 *
	 * @return array
	 */
	protected function getPackageProviders($app)
	{
		return [
			\Orchestra\Database\ConsoleServiceProvider::class,
			\iBrand\Poster\PhantoMmagickServiceProvider::class,
		];
	}
}