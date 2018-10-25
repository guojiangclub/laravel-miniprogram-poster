<?php

/*
 * This file is part of ibrand/laravel-miniprogram-poster.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
	//图片存储位置
	'disks'      => [
		'MiniProgramShare' => [
			'driver'     => 'local',
			'root'       => storage_path('app/public/share'),
			'url'        => env('APP_URL') . '/storage/share',
			'visibility' => 'public',
		],
	],
	//图片宽度
	'width'      => '575px',
	//放大倍数
	'zoomfactor' => 1.5,
	//1-9,9质量最高
	'quality'    => 9,
	//是否压缩图片
	'compress'   => true,
];
