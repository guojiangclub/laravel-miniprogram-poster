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
    'disks' => [
        'MiniProgramShare' => [
            'driver' => 'local',
            'root' => storage_path('app/public/share'),
            'url' => env('APP_URL').'/storage/share',
            'visibility' => 'public',
        ],
    ],
    'directory' => 'travel',
    //图片宽度
    'width' => '575',
    //放大倍数
    'zoomfactor' => 1.5,
    //0-100,100质量最高
    'quality' => 100,
    //是否压缩图片
    'compress' => true,
];
