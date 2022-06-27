<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('order_id')->unsigned()->index()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('complaints')->nullable();
            
            $table->bigInteger('staff_id')->unsigned()->index()->nullable();
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('cascade');

            $table->text('feedback')->nullable();
            $table->string('staff_members')->nullable();
            
            $table->integer('option_type')->comment('1-Deduction, 2-Free products to customers');
            
            $table->bigInteger('deduction_staff_id')->unsigned()->index()->nullable();
            $table->foreign('deduction_staff_id')->references('id')->on('staffs')->onDelete('cascade');

            $table->double('deduction_amount',16,2)->default(0.00);
            $table->bigInteger('free_order_id')->unsigned()->index()->nullable();
            $table->foreign('free_order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->tinyInteger('status')->default(0)->comment('0-not close, 1-close');

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
        Schema::dropIfExists('complaints');
    }
}
