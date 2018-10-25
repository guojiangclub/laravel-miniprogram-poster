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

		$this->seedGoods();
	}

	/**
	 * @param \Illuminate\Foundation\Application $app
	 */
	protected function getEnvironmentSetUp($app)
	{
		$app['config']->set('database.default', 'testing');
		$app['config']->set('database.connections.testing', [
			'driver'   => 'sqlite',
			'database' => ':memory:',
		]);
		$app['config']->set('repository.cache.enabled', true);

		$app['config']->set('ibrand.miniprogram-poster', require __DIR__ . '/../config/config.php');
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
			\Orchestra\Database\ConsoleServiceProvider::class,
			\iBrand\Miniprogram\Poster\PhantoMmagickServiceProvider::class,
		];
	}

	public function seedGoods()
	{
		GoodsTest::create([
			'name' => '女款 防水透气抓地耐磨越野跑鞋  A1NM',
		]);

		GoodsTest::create([
			'name' => 'The North Face 男款 硬壳夹克/冲锋衣 A55W',
		]);

		GoodsTest::create([
			'name' => 'The North Face男款 跑步鞋 A04F ',
		]);
	}
}