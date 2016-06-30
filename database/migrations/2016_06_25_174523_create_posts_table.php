<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pet_id')->unsigned();
            $table->foreign('pet_id')->references('id')->on('pets');

            $table->enum('type', ['lost', 'found']);

            $table->string('address');
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);

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
        Schema::drop('posts');
    }
}
