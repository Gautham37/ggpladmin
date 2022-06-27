<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driver_reviews', function (Blueprint $table) {
            $table->id();

            $table->text('review')->nullable();
            $table->tinyInteger('rate')->default(0);

            $table->bigInteger('driver_user_id')->unsigned()->index()->nullable();
            $table->foreign('driver_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('order_id')->unsigned()->index()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->bigInteger('vendor_stock_id')->unsigned()->index()->nullable();
            $table->foreign('vendor_stock_id')->references('id')->on('vendor_stock')->onDelete('cascade'); 

            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('driver_reviews');
    }
}
