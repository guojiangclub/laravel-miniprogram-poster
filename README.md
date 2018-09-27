# Laravel 小程序图文海报生成

* 项目基于[**PhantomMagick**](https://github.com/anam-hossain/phantommagick)


### 安装
```
composer require ibrand/laravel-miniprogram-poster 

```

* 依赖phantomjs。

* 非linux环境下安装：[phantomjs](http://phantomjs.org/download.html)

* mac环境下推荐使用 brew 安装phantomjs
```
    brew install phantomjs
```
* 如果laravel版本小于5.5,需要在config/app.php providers数组添加如下代码
```
    iBrand\Poster\PhantoMmagickServiceProvider::class
```
```
    php artisan storage:link
    php artisan vendor:publish --provider="iBrand\Poster\PhantoMmagickServiceProvider" --tag="config"
```
### 字体安装
* window 将下载的字体文件复制到C:Windows\Fonts目录下或者双击字体文件进行安装
* linux 
```
  cd /usr/share/fonts
  mkdir newfont
  #将下载的字体文件复制进这个目录
  cd newfont
  mkfontscale
  mkfontdir
  fc-cache
```
* mac 下载的字体文件 双击字体文件进行安装

### 配置项

``` 
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
```

### 示例
```
    use iBrand\Miniprogram\Poster\MiniProgramShareImg;
    
    $url = 'https://m.baidu.com/';
    $result = MiniProgramShareImg::generateShareImage($url);
    
    /*返回值：$result
    [
        'url'  => 'http://xxx.png',图片访问路径
        'path' => 'path/to/image', 图片相对路径
    ]
    /*
```

* 生成图片效果如下：<br/>
![效果图](http://admin.dev.tnf.ibrand.cc/storage/upload/image/72b60d1d2daa9395b7d502e74a08c138.png)