<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->dateTime('clock_in_time');
            $table->dateTime('clock_out_time')->nullable();
            $table->string('clock_in_ip');
            $table->string('clock_out_ip')->nullable();
            $table->string('working_from')->default('office');
            $table->enum('late',['yes','no']);
            $table->enum('half_day',['yes','no']);
            $table->enum('attendance_type',['regular','paid_leave'])->default('regular')->nullable();
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
        Schema::dropIfExists('attendances');
    }
}
