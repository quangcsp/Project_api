<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('author', 100)->nullable();
            $table->date('publish_date')->nullable();
            $table->integer('total_page')->nullable();
            $table->float('avg_star')->default(0);
            $table->string('code', 100)->unique();
            $table->integer('count_view')->default(0);
            $table->integer('category_id')->unsigned()->index();
            $table->integer('office_id')->unsigned()->index();
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
        Schema::dropIfExists('books');
    }
}
