<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_send_id')->unsigned()->index();
            $table->foreign('user_send_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('user_receive_id')->unsigned()->index();
            $table->integer('target_id')->index();
            $table->boolean('viewed')->default(false);
            $table->char('type', 20)->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
