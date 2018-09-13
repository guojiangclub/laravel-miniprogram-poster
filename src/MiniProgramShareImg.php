<?php

namespace iBrand\Poster;

use Anam\PhantomMagick\Converter;
use Illuminate\Support\Facades\Storage;

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

	public static function generateShareImage($dir, $saveName, $route)
	{
		if (!$dir || !$saveName || !$route) {
			return false;
		}

		$options = [
			'dimension'  => config('phantommagick.width', '575px'),
			'zoomfactor' => config('phantommagick.zoomfactor', 1.5),
			'quality'    => config('phantommagick.quality', 100),
		];

		$root = config('phantommagick.disks.MiniProgramShare.root');
		$file = $root . $dir . $saveName;

		try {
			$converter = self::init();

			$converter->source($route)->toPng($options)->save($file);

			self::imagePngSizeAdd($file);

			return Storage::disk('MiniProgramShare')->url($dir . $saveName);
		} catch (\Exception $exception) {

			return false;
		}
	}

	public static function imagePngSizeAdd($file)
	{
		list($width, $height, $type) = getimagesize($file);
		$new_width  = $width * 1;
		$new_height = $height * 1;

		//header('Content-Type:image/png');
		$resource = imagecreatetruecolor($new_width, $new_height);
		$image    = imagecreatefrompng($file);
		imagecopyresampled($resource, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagejpeg($resource, $file, config('phantommagick.quality'));
		imagedestroy($resource);
	}
}