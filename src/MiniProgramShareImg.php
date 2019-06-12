<?php

/*
 * This file is part of ibrand/laravel-miniprogram-poster.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Miniprogram\Poster;

use Illuminate\Database\Eloquent\Model;
use Storage;

class MiniProgramShareImg
{
    protected $config;

    protected $browserFactory;

    public function __construct($config,$browserFactory)
    {
        $this->config=$config;

        $this->browserFactory=$browserFactory;

    }

    /**
	 * 生成海报.
	 *
	 * @param $url
	 *
	 * @return array|bool
	 */
	public  function generateShareImage($url,$browser_factory=null)
	{
		if (!$url) {
			return false;
		}

        $browser_factory=$browser_factory?$browser_factory:config('ibrand.miniprogram-poster.default.browser_factory');
		$saveName = config('ibrand.miniprogram-poster.default.app') . '/' . date('Ymd') . '/' . md5(uniqid()) . '.png';
		$storage  = config('ibrand.miniprogram-poster.default.storage');
		$file     = config('ibrand.miniprogram-poster.disks.' . $storage . '.root') . '/' . $saveName;
        $browser_factory_info=config('ibrand.miniprogram-poster.browser_factory_info.'.$browser_factory);

        $browser = $this->browserFactory->createBrowser($browser_factory_info['options']);

        $page = $browser->createPage();

        $page->navigate($url)->waitForNavigation($browser_factory_info['wait_fornavigation_event'],$browser_factory_info['wait_fornavigation_time_out']);

        if ('qiniu' == $storage) {

            $base64=$page->screenshot()->getBase64();

            $img = base64_decode($base64);

            Storage::disk('qiniu')->put($saveName, $img);

		} else {

            $page->screenshot()->saveToFile($file);

			if (config('ibrand.miniprogram-poster.compress', true)) {
				$this->compress($file);
			}
		}

		$browser->close();

		if (Storage::disk($storage)->exists($saveName)) {

			return [
				'url'  => Storage::disk($storage)->url($saveName),
				'path' => $saveName,
			];

		}

		return false;
	}

	/**
	 * 压缩图片.
	 *
	 * @param $file
	 */
	public  function compress($file)
	{
		list($width, $height, $type) = getimagesize($file);
		$new_width  = $width * 1;
		$new_height = $height * 1;

		$resource = imagecreatetruecolor($new_width, $new_height);
		$image    = imagecreatefrompng($file);
		imagecopyresampled($resource, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagepng($resource, $file, config('ibrand.miniprogram-poster.quality', 9));
		imagedestroy($resource);
	}

	/**
	 * 绑定关系.
	 *
	 * @param Model $model
	 * @param array $path
	 *
	 * @return Poster
	 */
	public function attach(Model $model, array $path)
	{
		$poster = Poster::create(['content' => $path, 'posterable_id' => $model->id, 'posterable_type' => get_class($model)]);

		return $poster;
	}

	/**
	 * 关系是否存在.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 *
	 * @return mixed
	 */
	public function exists(Model $model)
	{
		$poster = Poster::where('posterable_id', $model->id)->where('posterable_type', get_class($model))->first();
		if ($poster) {
			return $poster;
		}

		return false;
	}

	/**
	 * 生成海报.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @param                                     $url
	 * @param bool                                $rebuild
	 *
	 * @return array|bool
	 */
	public function run(Model $model, $url, $rebuild = false)
	{
		$path   = [];
		$old    = [];
		$poster = $this->exists($model);
		if ($poster) {
			$path = $poster->content;
			$old  = $poster->content;
		}

		if ($rebuild || !$poster) {
			$path = $this->generateShareImage($url);
		}

		if (empty($path)) {
			return false;
		}

		if (!$poster) {
			$poster = $this->attach($model, $path);
		}

		if ($poster && $rebuild) {
			$poster->content = $path;
			$poster->save();
		}

		$storage = config('ibrand.miniprogram-poster.default.storage');
		if (config('ibrand.miniprogram-poster.delete', true) && !empty($old) && isset($old['path']) && Storage::disk($storage)->exists($old['path'])) {
			Storage::disk($storage)->delete($old['path']);
		}

		return $path;
	}
}
