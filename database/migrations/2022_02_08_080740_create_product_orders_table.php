<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_orders', function (Blueprint $table) {
            $table->id();

            /*$table->bigInteger('order_id')->unsigned()->index()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->bigInteger('product_id')->unsigned()->index()->nullable();
            $table->foreign('product_id')->references('id')->on('products');

            $table->double('price',16,2)->default(0.00);
            $table->double('quantity',16,3)->default(0.000);
            $table->string('unit')->nullable();
            $table->double('tax_percent',16,2)->default(0.00);
            $table->double('tax_amount',16,2)->default(0.00);*/


            $table->bigInteger('order_id')->unsigned()->index()->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->bigInteger('product_id')->unsigned()->index()->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->string('product_name')->nullable();
            $table->string('product_code')->nullable();
            //$table->string('product_hsn_code')->nullable();

            $table->double('quantity',16,3)->default(0.000);

            $table->bigInteger('unit')->unsigned()->index()->nullable();
            $table->foreign('unit')->references('id')->on('uom')->onDelete('cascade');

            $table->double('unit_price',16,2)->default(0.00);
            
            $table->double('discount')->default(0.00);
            $table->double('discount_amount',16,2)->default(0.00);

            $table->double('tax')->default(0.00);
            $table->double('tax_amount',16,2)->default(0.00);

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
        Schema::dropIfExists('product_orders');
    }
}
