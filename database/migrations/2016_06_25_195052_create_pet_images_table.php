<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pet_id')->unsigned()->nullable();
            $table->foreign('pet_id')->references('id')->on('pets')->onDelete('cascade');

            $table->string('image_url');
            $table->string('storage_path');

            $table->integer('width');
            $table->integer('height');

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
        Schema::drop('images');
    }
}
