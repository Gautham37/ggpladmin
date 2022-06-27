<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_notifications', function (Blueprint $table) {
            $table->id();
            
            $table->string('subject')->nullable();
            $table->text('message')->nullable();

            $table->bigInteger('party_type_id')->unsigned()->index()->nullable();
            $table->foreign('party_type_id')->references('id')->on('party_types')->onDelete('cascade');

            $table->bigInteger('party_sub_type_id')->unsigned()->index()->nullable();
            $table->foreign('party_sub_type_id')->references('id')->on('party_sub_types')->onDelete('cascade');
            
            $table->enum('type',['save','send','schedule'])->default('save')->nullable();
            $table->dateTime('schedule_date')->nullable();

            $table->enum('status',['draft','sent'])->default('draft')->nullable();

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
        Schema::dropIfExists('email_notifications');
    }
}
