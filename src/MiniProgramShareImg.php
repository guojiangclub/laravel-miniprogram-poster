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

	public static function generateShareImage($url, $type = 'default')
	{
		if (!$url) {
			return false;
		}

		$options = [
			'dimension'  => config('ibrand.miniprogram-poster.width', '575px'),
			'zoomfactor' => config('ibrand.miniprogram-poster.zoomfactor', 1.5),
			'quality'    => config('ibrand.miniprogram-poster.quality', 100),
		];

		$saveName = date('Ymd') . '/' . $type . '_' . md5(uniqid()) . '.png';
		$file     = config('ibrand.miniprogram-poster.disks.MiniProgramShare.root') . '/' . $saveName;

		$converter = self::init();

		$converter->source($url)->toPng($options)->save($file);

		if (config('ibrand.miniprogram-poster.compress', true)) {
			self::compress($file);
		}

		return [
			'url'  => Storage::disk('MiniProgramShare')->url($saveName),
			'path' => $saveName,
		];
	}

	public static function compress($file)
	{
		if (!file_exists($file)) {
			return;
		}

		list($width, $height, $type) = getimagesize($file);
		$new_width  = $width * 1;
		$new_height = $height * 1;

		$resource = imagecreatetruecolor($new_width, $new_height);
		$image    = imagecreatefrompng($file);
		imagecopyresampled($resource, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagepng($resource, $file, config('ibrand.miniprogram-poster.quality', 9));
		imagedestroy($resource);
	}

	public static function attach(Model $subject, array $path)
	{
		$poster = new Poster(['content' => $path]);

		$subject->posters()->save($poster);
	}

}
