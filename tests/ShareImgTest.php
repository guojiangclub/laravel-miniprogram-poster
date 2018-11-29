<?php

namespace iBrand\Miniprogram\Poster\Test;

use iBrand\Miniprogram\Poster\MiniProgramShareImg;
use iBrand\Miniprogram\Poster\Poster;
use Storage;

class ShareImgTest extends BaseTest
{
	/** @test */
	public function TestConfig()
	{
		$config = config('filesystems.disks');

		$this->assertArrayHasKey('MiniProgramShare', $config);
	}

	/** @test */
	public function TestGenerateShareImage()
	{
		config(['ibrand.miniprogram-poster.width' => '1300px']);

		$url    = 'https://www.ibrand.cc/';
		$result = MiniProgramShareImg::generateShareImage($url, 'ibrand');
		$this->assertTrue(Storage::disk('MiniProgramShare')->exists($result['path']));

		$result = MiniProgramShareImg::generateShareImage('');
		$this->assertFalse($result);
	}

	/** @test */
	public function TestShareImageV2()
	{
		config(['ibrand.miniprogram-poster.width' => '1300px']);

		$url   = 'https://www.ibrand.cc/';
		$goods = GoodsTestModel::find(1);

		//1. first build.
        $result = MiniProgramShareImg::run($goods, $url);
        $oldPath = $result['path'];
        $this->assertTrue(Storage::disk('MiniProgramShare')->exists($result['path']));
        $this->assertEquals(1, count($goods->posters));

        //2. rebuild and delete old.
        $result = MiniProgramShareImg::run($goods, $url,true);
        $oldPath2= $result['path'];
        $this->assertTrue(Storage::disk('MiniProgramShare')->exists($result['path']));
        $this->assertFalse(Storage::disk('MiniProgramShare')->exists($oldPath));
        $this->assertEquals(1, count($goods->posters));

        //3. rebuild but not delete old.
        $this->app['config']->set('ibrand.miniprogram-poster.delete',false);
        $result = MiniProgramShareImg::run($goods, $url,true);
        $this->assertTrue(Storage::disk('MiniProgramShare')->exists($result['path']));
        $this->assertTrue(Storage::disk('MiniProgramShare')->exists($oldPath2));

		$poster = Poster::find(1);
		$this->assertEquals(GoodsTestModel::class, get_class($poster->posterable));
	}
}