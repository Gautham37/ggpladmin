<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyalityPointTrackerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyality_points_tracker', function (Blueprint $table) {
            $table->id();

            $table->string('affiliate_id')->index()->nullable();
            $table->foreign('affiliate_id')->references('affiliate_id')->on('users')->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->enum('category',['referral','sales_invoice','online_order']);
            $table->enum('type',['earn','redeem']);

            $table->double('points')->default(0);
            $table->double('amount',16,2)->default(0.00);
            
            $table->string('referee_mobile')->nullable();
            
            $table->bigInteger('sales_invoice_id')->unsigned()->index()->nullable();
            $table->foreign('sales_invoice_id')->references('id')->on('sales_invoice')->onDelete('cascade');

            $table->bigInteger('order_id')->unsigned()->index()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

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
        Schema::dropIfExists('loyality_points_tracker');
    }
}
