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
		$route = 'https://m.baidu.com/';

		$file = MiniProgramShareImgTest::generateShareImage($route);
		$this->assertTrue(file_exists(__DIR__ . '/' . $file));
	}
}