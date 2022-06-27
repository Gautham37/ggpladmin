<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentOutSettleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_out_settle', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('payment_out_id')->unsigned()->index()->nullable();
            $table->foreign('payment_out_id')->references('id')->on('payment_out')->onDelete('cascade');
            
            $table->enum('settle_type',['sales','purchase']);
            $table->double('amount',16,2)->default(0.00);

            $table->bigInteger('sales_return_id')->unsigned()->index()->nullable();
            $table->foreign('sales_return_id')->references('id')->on('sales_return')->onDelete('cascade');
            
            $table->bigInteger('purchase_invoice_id')->unsigned()->index()->nullable();
            $table->foreign('purchase_invoice_id')->references('id')->on('purchase_invoice')->onDelete('cascade');

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
        Schema::dropIfExists('payment_out_settle');
    }
}
