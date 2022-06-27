<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyalityPointUsageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyality_point_usage', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('order_id')->unsigned()->index()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            
            $table->double('points')->default(0);
            $table->double('amount',16,2)->default(0.00);

            $table->enum('order_type',['sales_invoice','online_order']);

            $table->bigInteger('sales_invoice_id')->unsigned()->index()->nullable();
            $table->foreign('sales_invoice_id')->references('id')->on('sales_invoice')->onDelete('cascade');

            $table->bigInteger('sales_return_id')->unsigned()->index()->nullable();
            $table->foreign('sales_return_id')->references('id')->on('sales_return')->onDelete('cascade');

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
        Schema::dropIfExists('loyality_point_usage');
    }
}
