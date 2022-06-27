<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerSlideTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_slide', function (Blueprint $table) {
            $table->id();
            $table->integer('order');
            $table->string('text')->nullable();
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('farmer_slide');
    }
}
