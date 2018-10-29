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

		$result = MiniProgramShareImg::run($goods, $url, true);
		$this->assertTrue(Storage::disk('MiniProgramShare')->exists($result['path']));
		$this->assertSame(1, count($goods->posters));

		$result = MiniProgramShareImg::run($goods, $url);
		$this->assertTrue(Storage::disk('MiniProgramShare')->exists($result['path']));

		$result = MiniProgramShareImg::run($goods, $url, true);
		$this->assertTrue(Storage::disk('MiniProgramShare')->exists($result['path']));
		$this->assertSame(1, count($goods->posters));

		$poster = Poster::find(1);
		$this->assertSame(1, count($poster->posterable));
		$this->assertSame(GoodsTestModel::class, get_class($poster->posterable));
	}
}