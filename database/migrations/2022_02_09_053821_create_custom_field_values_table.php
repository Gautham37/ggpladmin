<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->longText('value');
            $table->longText('view');   

            $table->bigInteger('custom_field_id')->unsigned()->index()->nullable();
            $table->foreign('custom_field_id')->references('id')->on('custom_fields')->onDelete('cascade');

            $table->string('customizable_type');
            $table->integer('customizable_id');

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
        Schema::dropIfExists('custom_field_values');
    }
}
