<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('product_id')->unsigned()->index()->nullable();
            $table->foreign('product_id')->references('id')->on('products');    
            $table->integer('order')->default(0);
            $table->string('text')->nullable();
            $table->string('button')->nullable();
            $table->string('text_position')->default('start');
            $table->string('text_color')->nullable();
            $table->string('button_color')->nullable();
            $table->string('background_color')->nullable();
            $table->string('indicator_color')->nullable();
            $table->string('image_fit')->default('cover');
            
            $table->tinyInteger('enabled')->default(1);

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
        Schema::dropIfExists('slides');
    }
}
