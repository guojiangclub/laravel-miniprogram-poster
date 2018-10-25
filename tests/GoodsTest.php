<?php

namespace iBrand\Poster\Test;

use iBrand\Miniprogram\Poster\HasPoster;
use Illuminate\Database\Eloquent\Model;

class GoodsTest extends Model
{
	use HasPoster;

	public $table = 'goods';

	public $guarded = ['id'];
}