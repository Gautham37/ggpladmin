<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code');
            $table->string('refund_order_code')->nullable();

            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->bigInteger('order_status_id')->unsigned()->index()->nullable();
            $table->foreign('order_status_id')->references('id')->on('order_statuses');

            $table->double('tax',16,2)->default(0.00);
            $table->double('delivery_fee',16,2)->default(0.00);
            $table->double('delivery_distance')->default(0);
            $table->double('redeem_amount',16,2)->default(0.00);
            $table->double('coupon_amount',16,2)->default(0.00);
            $table->double('contribution_amount',16,2)->default(0.00);
            $table->double('order_amount',16,2)->default(0.00);

            $table->text('hint')->nullable();
            $table->boolean('active')->nullable()->default(1);

            $table->bigInteger('driver_id')->unsigned()->index()->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers');

            $table->bigInteger('delivery_address_id')->unsigned()->index()->nullable();
            $table->foreign('delivery_address_id')->references('id')->on('delivery_addresses');

            $table->bigInteger('payment_id')->unsigned()->index()->nullable();
            //$table->foreign('payment_id')->references('id')->on('payments');

            $table->enum('status',['order_placed','preparing','ready','on-the-way','delivered','cancelled'])->default('order_placed');

            $table->tinyInteger('is_deleted')->default('0');

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
        Schema::dropIfExists('orders');
    }
}
