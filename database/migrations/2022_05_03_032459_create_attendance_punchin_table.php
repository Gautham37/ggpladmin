<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancePunchinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_punchin', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('attendance_id')->unsigned()->index()->nullable();
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
            $table->dateTime('punch_time');
            $table->enum('punch_type',['punch_in','punch_out']);
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
        Schema::dropIfExists('attendance_punchin');
    }
}
