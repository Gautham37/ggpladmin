<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOrderOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_order_options', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('product_order_id')->unsigned()->index()->nullable();
            $table->foreign('product_order_id')->references('id')->on('product_orders')->onDelete('cascade');
            
            $table->bigInteger('option_id')->unsigned()->index()->nullable();
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');

            $table->double('price',16,2)->default(0.00);

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
        Schema::dropIfExists('product_order_options');
    }
}
