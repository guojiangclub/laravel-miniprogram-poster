<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosterTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('posters')) {
            Schema::create('posters', function (Blueprint $table) {
                $table->increments('id');
                $table->text('content')->nullable();
                $table->integer('posterable_id')->unsigned();
                $table->string('posterable_type');
                $table->timestamps();
                $table->index(['posterable_id', 'posterable_type']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('posters');
    }
}