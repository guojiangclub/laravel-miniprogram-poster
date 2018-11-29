<?php

namespace iBrand\Miniprogram\Poster\Test;

use iBrand\Miniprogram\Poster\HasPoster;
use Illuminate\Database\Eloquent\Model;

class GoodsTestModel extends Model
{
	use HasPoster;

	public $table = 'goods';

	public $guarded = ['id'];
}