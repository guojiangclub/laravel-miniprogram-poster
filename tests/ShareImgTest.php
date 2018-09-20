<?php

namespace iBrand\Poster\Test;

use iBrand\Miniprogram\Poster\MiniProgramShareImg;
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
		$route = 'https://m.baidu.com/';

		$url = MiniProgramShareImg::generateShareImage($route);
		$this->assertTrue(Storage::disk('MiniProgramShare')->exists($url));
	}
}