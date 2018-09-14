<?php

namespace iBrand\Poster\Test;

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
		$saveName = config('phantommagick.directory') . '/' . md5(time() . mt_rand(10000, 99999)) . '_share' . '.png';
		$route    = 'https://m.baidu.com/';

		MiniProgramShareImgTest::generateShareImage($saveName, $route);
		$this->assertTrue(file_exists(__DIR__ . '/' . $saveName));
	}
}