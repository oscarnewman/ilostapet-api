<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->enum('animal', ['dog', 'cat']);
            $table->enum('gender', ['male', 'female']);

            $table->string('name')->nullable();
            $table->string('breed')->nullable();
            $table->string('description')->nullable();

            $table->boolean('has_collar');
            $table->boolean('has_tags');
            $table->boolean('has_microchip');

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
        Schema::drop('pets');
    }
}
