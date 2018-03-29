<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('book_id')->unsigned()->index();
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->smallInteger('status')->unsigned()->nullable();
            $table->char('type',20)->nullable();
            $table->integer('owner_id')->unsigned()->nullable()->index();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('days_to_read')->unsigned()->default(config('settings.default_days_to_read'));
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
        Schema::dropIfExists('book_user');
    }
}
