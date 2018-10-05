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
		config(['ibrand.miniprogram-poster.width' => '1300px']);

		$url    = 'https://www.ibrand.cc/';
		$result = MiniProgramShareImg::generateShareImage($url, 'ibrand');
		$this->assertTrue(Storage::disk('MiniProgramShare')->exists($result['path']));

		$result = MiniProgramShareImg::generateShareImage('');
		$this->assertFalse($result);
	}
}