<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('values');
            $table->tinyInteger('disabled')->nullable();
            $table->tinyInteger('required')->nullable();
            $table->tinyInteger('in_table')->nullable();
            $table->tinyInteger('bootstrap_column')->nullable();
            $table->tinyInteger('order')->nullable();
            $table->string('custom_field_model');
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
        Schema::dropIfExists('custom_fields');
    }
}
