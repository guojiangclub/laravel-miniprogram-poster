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
		$savePath = '/baidu/';
		$saveName = md5(time() . mt_rand(10000, 99999)) . '_share' . '.png';
		$route    = 'https://m.baidu.com/';

		MiniProgramShareImgTest::generateShareImage($savePath, $saveName, $route);
		$this->assertTrue(file_exists(__DIR__ . $savePath . $saveName));
	}
}