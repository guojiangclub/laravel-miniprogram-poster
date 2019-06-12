<?php

/*
 * This file is part of ibrand/laravel-miniprogram-poster.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use HeadlessChromium\Page;

return [

    //环境变量“chrome_path”，活着chrome可执行文件
    'chromeBinaries'=>'chromium-browser',

	'default'    => [
		'storage' => env('DEFAULT_POSTER_STORAGE', 'qiniu'),
		'app'     => env('APP_NAME', 'default'),

        'browser_factory'=>env('DEFAULT_POSTER_BROWSER_FACTORY', 'chromium'),
	],
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

    //浏览器设置
    'browser_factory_info'=>[

        'chromium'=>[

            'options'=>[

               'windowSize' => [375,667]

             ],
             'wait_fornavigation_event'=>page::NETWORK_IDLE,

             'wait_fornavigation_time_out'=>10000

        ],

        //....可自行添加初始化配置详情见（https://github.com/chrome-php/headless-chromium-php）

    ],


	//是否压缩图片
	'compress'   => true,
	//是否删除废弃图片文件
	'delete'     => true,
];
