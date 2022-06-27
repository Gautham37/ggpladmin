<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_track', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('sales_invoice_id')->unsigned()->index()->nullable();
            $table->foreign('sales_invoice_id')->references('id')->on('sales_invoice')->onDelete('cascade');

            $table->bigInteger('sales_return_id')->unsigned()->index()->nullable();
            $table->foreign('sales_return_id')->references('id')->on('sales_return')->onDelete('cascade');

            $table->bigInteger('purchase_invoice_id')->unsigned()->index()->nullable();
            $table->foreign('purchase_invoice_id')->references('id')->on('purchase_invoice')->onDelete('cascade');

            $table->bigInteger('purchase_return_id')->unsigned()->index()->nullable();
            $table->foreign('purchase_return_id')->references('id')->on('purchase_return')->onDelete('cascade');

            $table->bigInteger('payment_in_id')->unsigned()->index()->nullable();
            $table->foreign('payment_in_id')->references('id')->on('payment_in')->onDelete('cascade');

            $table->bigInteger('payment_out_id')->unsigned()->index()->nullable();
            $table->foreign('payment_out_id')->references('id')->on('payment_out')->onDelete('cascade');

            $table->bigInteger('order_id')->unsigned()->index()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->enum('category',['sales','sales_return','purchase','purchase_return','payment_in','payment_out','online','online_return']);
            $table->enum('type',['credit', 'debit']);
            $table->date('date');

            $table->bigInteger('market_id')->unsigned()->index()->nullable();
            $table->foreign('market_id')->references('id')->on('markets')->onDelete('cascade');

            $table->double('amount',16,2)->default(0.00);

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
        Schema::dropIfExists('transaction_track');
    }
}
