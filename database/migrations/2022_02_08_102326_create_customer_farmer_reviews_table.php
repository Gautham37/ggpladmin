<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFarmerReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_farmer_reviews', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('driver_id')->unsigned()->index()->nullable();
            $table->foreign('driver_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->text('review')->nullable();
            $table->tinyInteger('rate')->default(0);
            $table->string('option_type')->nullable()->comment('1-customer, 2-farmer');
            $table->integer('option_id')->comment('order_id, supplier_id');

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
        Schema::dropIfExists('customer_farmer_reviews');
    }
}
