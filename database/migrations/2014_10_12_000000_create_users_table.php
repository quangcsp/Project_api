<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->unique();
            $table->string('password')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('code', 10)->unique()->nullable();
            $table->string('position')->nullable();
            $table->char('role', 10)->default('user');
            $table->integer('office_id')->unsigned()->index()->nullable();
            $table->string('avatar')->nullable();
            $table->text('tags')->nullable();
            $table->string('employee_code', 10)->nullable();
            $table->string('workspaces', 50)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
