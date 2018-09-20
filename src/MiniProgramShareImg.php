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

    public static function generateShareImage($route)
    {
        if (!$route) {
            return false;
        }

        $options = [
            'dimension' => config('ibrand.miniprogram-poster.width', '575px'),
            'zoomfactor' => config('ibrand.miniprogram-poster.zoomfactor', 1.5),
            'quality' => config('ibrand.miniprogram-poster.quality', 100),
        ];

        $saveName = config('ibrand.miniprogram-poster.directory').'/'.md5(uniqid()).'.png';
        $root = config('ibrand.miniprogram-poster.disks.MiniProgramShare.root');
        $file = $root.'/'.$saveName;

        try {
            $converter = self::init();

            $converter->source($route)->toPng($options)->save($file);

            if (config('ibrand.miniprogram-poster.compress', true)) {
                self::imagePngSizeAdd($file);
            }

            return Storage::disk('MiniProgramShare')->url($saveName);
        } catch (\Exception $exception) {
            return false;
        }
    }

    public static function imagePngSizeAdd($file)
    {
        list($width, $height, $type) = getimagesize($file);
        $new_width = $width * 1;
        $new_height = $height * 1;

        //header('Content-Type:image/png');
        $resource = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefrompng($file);
        imagecopyresampled($resource, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($resource, $file, config('ibrand.miniprogram-poster.quality'));
        imagedestroy($resource);
    }
}
