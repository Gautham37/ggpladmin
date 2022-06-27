<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints_comments', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('complaints_id')->unsigned()->index()->nullable();
            $table->foreign('complaints_id')->references('id')->on('complaints')->onDelete('cascade');
            $table->bigInteger('staff_id')->unsigned()->index()->nullable();
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('cascade');
            $table->string('comments')->nullable();

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
        Schema::dropIfExists('complaints_comments');
    }
}
