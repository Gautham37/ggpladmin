<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartySubTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('party_sub_types', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('party_type_id')->unsigned()->index()->nullable();
            $table->foreign('party_type_id')->references('id')->on('party_types')->onDelete('cascade');

            $table->string('name', 127);
            $table->string('prefix_value', 127);
            $table->text('description')->nullable();
            $table->boolean('active')->nullable()->default(1);

            $table->bigInteger('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->bigInteger('updated_by')->unsigned()->index()->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('party_sub_types');
    }
}
