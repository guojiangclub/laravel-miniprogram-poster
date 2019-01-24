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

use Anam\PhantomMagick\Converter;
use Illuminate\Database\Eloquent\Model;
use Storage;

class MiniProgramShareImg
{
	public static $conv = null;

	public static function init()
	{
		if (is_null(self::$conv) || !self::$conv instanceof Converter) {
			self::$conv = new Converter();
		}

		return self::$conv;
	}

	/**
	 * 生成海报.
	 *
	 * @param $url
	 *
	 * @return array|bool
	 */
	public static function generateShareImage($url)
	{
		if (!$url) {
			return false;
		}

		$options = [
			'dimension'  => config('ibrand.miniprogram-poster.width', '575px'),
			'zoomfactor' => config('ibrand.miniprogram-poster.zoomfactor', 1.5),
			'quality'    => config('ibrand.miniprogram-poster.quality', 100),
		];

		$saveName = config('ibrand.miniprogram-poster.default.app') . '/' . date('Ymd') . '/' . md5(uniqid()) . '.png';
		$storage  = config('ibrand.miniprogram-poster.default.storage');
		$file     = config('ibrand.miniprogram-poster.disks.' . $storage . '.root') . '/' . $saveName;

		$converter = self::init();

		$converter->source($url)->toPng($options)->save($file);

		if (config('ibrand.miniprogram-poster.compress', true)) {
			self::compress($file);
		}

		if ('qiniu' == $storage) {
			Storage::disk($storage)->put($saveName, file_get_contents($file));
		}

		return [
			'url'  => Storage::disk($storage)->url($saveName),
			'path' => $saveName,
		];
	}

	/**
	 * 压缩图片.
	 *
	 * @param $file
	 */
	public static function compress($file)
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
	public static function attach(Model $model, array $path)
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
	public static function exists(Model $model)
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
	public static function run(Model $model, $url, $rebuild = false)
	{
		$path   = [];
		$poster = self::exists($model);
		if ($poster) {
			$path = $poster->content;
		}

		if ($rebuild || !$poster) {
			$path = self::generateShareImage($url);
		}

		if (!$poster) {
			self::attach($model, $path);
		}

		if ($poster && $rebuild) {
			$old     = $poster->content;
			$storage = config('ibrand.miniprogram-poster.default.storage');
			if (config('ibrand.miniprogram-poster.delete', true) && !empty($old) && isset($old['path']) && Storage::disk($storage)->exists($old['path'])) {
				Storage::disk($storage)->delete($old['path']);
			}
			$poster->content = $path;
			$poster->save();
		}

		return $path;
	}
}
