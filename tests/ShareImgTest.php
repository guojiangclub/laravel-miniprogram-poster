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
		$url    = 'https://m.baidu.com/';
		$result = MiniProgramShareImg::generateShareImage($url, 'travel');
		$this->assertTrue(Storage::disk('MiniProgramShare')->exists($result['path']));

		$result = MiniProgramShareImg::generateShareImage('');
		$this->assertFalse($result);

		$url    = 'www.xxxx.com/';
		$result = MiniProgramShareImg::generateShareImage($url);
		$this->assertFalse($result);
	}
}