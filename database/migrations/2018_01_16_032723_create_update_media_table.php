<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpdateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('update_media', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('update_book_id')->unsigned();
            $table->foreign('update_book_id')->references('id')->on('update_books')->onDelete('cascade');
            $table->integer('media_id')->unsigned()->nullable();
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('path');
            $table->integer('size')->default(0);
            $table->boolean('type')->default(false);
            $table->string('thumb_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('update_media');
    }
}
