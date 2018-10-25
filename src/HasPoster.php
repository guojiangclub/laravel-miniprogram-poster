<?php

namespace iBrand\Miniprogram\Poster;

trait HasPoster
{
	public static function getPosterClassName()
	{
		return Poster::class;
	}

	public function posters()
	{
		return $this->morphMany(self::getPosterClassName(), 'posterable');
	}
}