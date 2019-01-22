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
	'default'    => [
		'storage' => env('DEFAULT_POSTER_STORAGE', 'qiniu'),
	],
	//图片存储位置
	'disks'      => [
		'qiniu'            => [
			'driver'     => 'qiniu',
			//七牛云access_key
			'access_key' => env('QINIU_ACCESS_KEY', ''),
			//七牛云secret_key
			'secret_key' => env('QINIU_SECRET_KEY', ''),
			//七牛云文件上传空间
			'bucket'     => env('QINIU_BUCKET', ''),
			//七牛云cdn域名
			'domain'     => env('QINIU_DOMAIN', ''),
			//与cdn域名保持一致
			'url'        => env('QINIU_DOMAIN', ''),
			'root'       => storage_path('app/public/qiniu'),
		],
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
	//是否删除废弃图片文件
	'delete'     => true,
];
