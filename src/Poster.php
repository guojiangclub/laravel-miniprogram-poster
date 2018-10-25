<?php

namespace iBrand\Miniprogram\Poster;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poster extends Model
{
	use SoftDeletes;

	public $table = 'posters';

	public $guarded = ['id'];

	public function posterable()
	{
		return $this->morphTo();
	}

	public function getContentAttribute($value)
	{
		return json_decode($value, true);
	}

	public function setContentAttribute($value)
	{
		if (is_array($value)) {
			$this->attributes['content'] = json_encode($value);
		}
	}
}