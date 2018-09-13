<?php

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
	//0-100,100质量最高
	'quality'    => 100,
	//是否压缩图片
	'compress'   => true,
];